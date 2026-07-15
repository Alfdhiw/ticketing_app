<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\EventStatusHistory;
use App\Http\Requests\EventFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventsExport;

class EventController extends Controller
{
    private function ensureAdmin(): void
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->ensureAdmin();

        $query = Event::query()->with(['kategori', 'tikets']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('status')) {
            if ($request->status === 'Upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'Ongoing') {
                $query->ongoing();
            } elseif ($request->status === 'Completed') {
                $query->completed();
            }
        }

        $query->orderBy('tanggal_waktu', $request->get('sort') === 'oldest' ? 'asc' : 'desc');

        $events = $query->paginate(10);
        $kategoris = Kategori::all();

        return view('admin.events.index', compact('events', 'kategoris'));
    }

    public function export()
    {
        $this->ensureAdmin();

        return Excel::download(new EventsExport, 'events.xlsx');
    }

    public function bulkDelete(Request $request)
    {
        $this->ensureAdmin();

        $ids = $request->input('ids');
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada event yang dipilih.');
        }

        $events = Event::whereIn('id', $ids)->get();
        $deletedCount = 0;
        foreach ($events as $event) {
            if (!$event->hasSales()) {
                if ($event->gambar && Storage::disk('public')->exists($event->gambar)) {
                    Storage::disk('public')->delete($event->gambar);
                }
                $event->delete();
                $deletedCount++;
            }
        }

        return redirect()->back()->with('success', "$deletedCount event berhasil dihapus secara massal.");
    }

    public function clone(Event $event)
    {
        $this->ensureAdmin();

        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($event, &$newEvent) {
            $newEvent = $event->replicate();
            $newEvent->judul = $event->judul . ' (Copy)';
            $newEvent->save();

            foreach ($event->tikets as $tiket) {
                $newTiket = $tiket->replicate();
                $newTiket->event_id = $newEvent->id;
                $newTiket->save();
            }

            EventStatusHistory::create([
                'event_id' => $newEvent->id,
                'status' => $newEvent->status
            ]);
        });

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diduplikasi.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->ensureAdmin();

        $kategoris = Kategori::all();
        return view('admin.events.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventFormRequest $request)
    {
        $this->ensureAdmin();

        $validated = $request->validated();

        $imagePath = $request->hasFile('gambar')
            ? $request->file('gambar')->store('events', 'public')
            : 'konser.jpg';

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $imagePath) {
            $event = auth()->user()->events()->create([
                'judul' => $validated['judul'],
                'kategori_id' => $validated['kategori_id'],
                'deskripsi' => $validated['deskripsi'],
                'lokasi' => $validated['lokasi'],
                'tanggal_waktu' => $validated['tanggal_waktu'],
                'gambar' => $imagePath,
            ]);

            foreach ($validated['tikets'] as $tiketData) {
                $event->tikets()->create([
                    'tipe' => $tiketData['tipe'],
                    'harga' => $tiketData['harga'],
                    'stok' => $tiketData['stok'],
                ]);
            }

            EventStatusHistory::create([
                'event_id' => $event->id,
                'status' => $event->status
            ]);
        });

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['kategori', 'tikets']);
        $relatedEvents = Event::where('kategori_id', $event->kategori_id)
            ->where('id', '!=', $event->id)
            ->upcoming()
            ->take(4)
            ->get();

        return view('events.show', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->ensureAdmin();

        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $event->load(['tikets', 'eventStatusHistories']);
        $kategoris = Kategori::all();
        return view('admin.events.edit', compact('event', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventFormRequest $request, Event $event)
    {
        $this->ensureAdmin();

        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        if ($event->hasSales()) {
            unset($validated['tanggal_waktu']);
            unset($validated['tikets']);
        }

        if ($request->hasFile('gambar')) {
            if ($event->gambar && $event->gambar !== 'konser.jpg' && Storage::disk('public')->exists($event->gambar)) {
                Storage::disk('public')->delete($event->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('events', 'public');
        }

        $oldStatus = $event->status;

        \Illuminate\Support\Facades\DB::transaction(function () use ($event, $validated, $oldStatus, $request) {
            $event->update($validated);

            if ($oldStatus !== $event->status) {
                EventStatusHistory::create([
                    'event_id' => $event->id,
                    'status' => $event->status
                ]);
            }

            if (!$event->hasSales()) {
                $event->tikets()->delete();
                foreach ($request->tikets ?? [] as $tiketData) {
                    $event->tikets()->create([
                        'tipe' => $tiketData['tipe'],
                        'harga' => $tiketData['harga'],
                        'stok' => $tiketData['stok'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->ensureAdmin();

        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        if ($event->hasSales()) {
            return redirect()->route('admin.events.index')->with('error', 'Tidak dapat menghapus event yang sudah memiliki penjualan tiket.');
        }

        if ($event->gambar && $event->gambar !== 'konser.jpg' && Storage::disk('public')->exists($event->gambar)) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
