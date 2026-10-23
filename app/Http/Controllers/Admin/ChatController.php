<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        // Dapatkan daftar percakapan terbaru
        $conversations = ChatMessage::select('user_id')
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'email');
            }])
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.chat.index', compact('conversations'));
    }

    public function getMessages($userId)
    {
        // Tandai pesan dari user ini sebagai sudah dibaca
        ChatMessage::where('user_id', $userId)
            ->where('is_from_user', true)
            ->update(['is_read' => true]);

        // Ambil pesan untuk user ini
        $messages = ChatMessage::where('user_id', $userId)
            ->orWhere(function($query) use ($userId) {
                $query->where('admin_id', Auth::id())
                      ->where('user_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'user_id' => $request->user_id,
            'admin_id' => Auth::id(),
            'message' => $request->message,
            'is_from_user' => false,
            'is_read' => true,
        ]);

        // TODO: Tambahkan event untuk real-time notification
        // event(new NewAdminMessage($message));

        return response()->json([
            'status' => 'success', 
            'message' => $message->load('admin')
        ]);
    }

    public function unreadCount()
    {
        $count = ChatMessage::where('is_from_user', true)
            ->where('is_read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}
