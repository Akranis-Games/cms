<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tickets = auth()->user()->supportTickets()->latest()->get();
        return view('support.index', compact('tickets'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        SupportTicket::create([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('support.index')->with('success', 'Ticket erfolgreich erstellt');
    }

    public function show(SupportTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && !auth()->user()->isModerator()) {
            abort(403);
        }

        $replies = $ticket->replies()->with('user')->latest()->get();
        return view('support.show', compact('ticket', 'replies'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        if (auth()->user()->isModerator() && $ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Antwort erfolgreich hinzugefügt');
    }
}

