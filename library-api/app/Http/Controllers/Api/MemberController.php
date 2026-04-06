<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Http\Resources\MemberResource;

class MemberController extends Controller
{
    // GET /api/members
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('member_code', 'like', '%' . $request->search . '%');
        }

        return MemberResource::collection($query->orderBy('name')->get());
    }

    // GET /api/members/{id}
    public function show(string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new MemberResource($member)
        ]);
    }

    // POST /api/members
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'member_code' => 'required|string|max:20|unique:members,member_code',
            'email'       => 'required|email|unique:members,email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'status'      => 'nullable|in:active,inactive,suspended',
            'joined_at'   => 'nullable|date',
        ]);

        $member = Member::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Member berhasil didaftarkan.',
            'data'    => new MemberResource($member),
        ], 201);
    }

    // PUT /api/members/{id}
    public function update(Request $request, string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:150',
            'member_code' => 'sometimes|string|max:20|unique:members,member_code,' . $id,
            'email'       => 'sometimes|email|unique:members,email,' . $id,
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'status'      => 'nullable|in:active,inactive,suspended',
            'joined_at'   => 'nullable|date',
        ]);

        $member->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Member berhasil diupdate.',
            'data'    => new MemberResource($member),
        ], 200);
    }

    // DELETE /api/members/{id}
    public function destroy(string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan.'
            ], 404);
        }

        // cek status
        if ($member->status === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Member aktif tidak boleh dihapus.'
            ], 422);
        }

        $name = $member->name;
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => "Member '{$name}' berhasil dihapus."
        ], 200);
    }
}