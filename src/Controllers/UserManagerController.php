<?php

namespace admin\users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\users\Requests\UserCreateRequest;
use admin\users\Requests\UserUpdateRequest;
use admin\users\Models\User;
use admin\users\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use admin\users\Mail\WelcomeMail;

class UserManagerController extends Controller
{

    public function __construct()
    {
        $this->middleware('admincan_permission:users_manager_list')->only(['index']);
        $this->middleware('admincan_permission:users_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:users_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:users_manager_view')->only(['show']);
        $this->middleware('admincan_permission:users_manager_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $type)
    {
        try {
            $role = UserRole::where('slug', $type)->firstOrFail();

            $users = User::where('role_id', $role->id)
                ->filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->latest()
                ->paginate(User::getPerPageLimit())
                ->withQueryString();

            return view('user::admin.index', compact('users', 'role', 'type'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }

    public function create(string $type)
    {
        try {
            $role = UserRole::where('slug', $type)->firstOrFail();
            return view('user::admin.createOrEdit', compact('role', 'type'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }

    public function store(UserCreateRequest $request, string $type)
    {
        try {
            $role = UserRole::where('slug', $type)->firstOrFail();
            $requestData = $request->validated();
            $requestData['role_id'] = $role->id;

            $plainPassword = \Str::random(8);
            $requestData['password'] = Hash::make($plainPassword);

            // Create user
            $user = User::create($requestData);

            // Send welcome mail
            Mail::to($user->email)->send(new WelcomeMail($user, $plainPassword));
            return redirect()->route('admin.users.index', ['type' => $type])->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }

    /**
     * show user details
     */
    public function show(string $type, User $user)
    {
        try {
            $role = UserRole::where('slug', $type)->firstOrFail();
            return view('user::admin.show', compact('user', 'type', 'role'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load users: ' . $e->getMessage());
        }
    }

    public function edit(string $type, User $user)
    {
        try {
            $role = UserRole::where('slug', $type)->firstOrFail();
            return view('user::admin.createOrEdit', compact('user', 'type', 'role'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load user for editing: ' . $e->getMessage());
        }
    }

    public function update(UserUpdateRequest $request, string $type, User $user)
    {
        try {
            $requestData = $request->validated();

            $user->update($requestData);
            return redirect()->route('admin.users.index', ['type' => $type])->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load user for editing: ' . $e->getMessage());
        }
    }

    public function destroy(string $type, User $user)
    {
        try {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, string $type)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->status = $request->status;
            $user->save();

            // create status html dynamically        
            $dataStatus = $user->status == '1' ? '0' : '1';
            $label = $user->status == '1' ? 'Active' : 'InActive';
            $btnClass = $user->status == '1' ? 'btn-success' : 'btn-warning';
            $tooltip = $user->status == '1' ? 'Click to change status to inactive' : 'Click to change status to active';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.users.updateStatus', ['type' => $type]) . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $user->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }
}
