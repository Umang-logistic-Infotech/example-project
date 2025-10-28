<div class="d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <h2>Welcome to the {{ $pagename }} Page</h2>
    </div>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            {{-- <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="rounded-circle me-2" width="40"
                height="40"> --}}
            {{-- <span class="fw-semibold text-light">{{ $user->name }}</span> --}}
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</div>
