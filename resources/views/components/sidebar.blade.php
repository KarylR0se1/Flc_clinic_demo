@auth
<div class="sidebar" id="sidebar">
    <!-- Burger Button -->
    <div class="burger-container ml-auto">
        <button class="burger-btn" id="burgerToggle">
            <i class="bi bi-list text-white" style="font-size: 1.5rem;"></i>
        </button>
    </div>

    <!-- Sidebar Header -->
    @php
        $user = Auth::user();
        $profilePicture = $user->profile_picture
            ?? ($user->doctor->profile_picture ?? null)
            ?? ($user->patient->profile_picture ?? null)
            ?? ($user->admin->profile_picture ?? null);
    @endphp

    <div class="sidebar-header d-flex align-items-center gap-3 p-3">
        <div class="position-relative">
            @if($profilePicture)
                <img 
                    src="{{ asset('storage/' . $profilePicture) }}" 
                    class="rounded-circle sidebar-img"
                    alt="Profile Picture"
                    title="{{ $user->name }} - {{ $user->role ?? '' }}"
                >
            @else
                <img 
                    src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" 
                    class="rounded-circle sidebar-img"
                    alt="Profile Picture"
                >
            @endif
        </div>
        <div class="sidebar-user-info">
            <h6 class="text-white fw-bold mb-0 sidebar-name">{{ $user->name }}</h6>
            <small class="text-light sidebar-role text-uppercase">{{ $user->role ?? '' }}</small>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar-menu mt-3 w-100">
        {{-- Patient Links --}}
        @if($type === 'patient')
            <a href="{{ route('redirect.by.role') }}" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> <span class="sidebar-text">Home</span>
            </a>
            <a href="{{ route('appointments.history') }}" class="{{ request()->routeIs('appointments.history') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> <span class="sidebar-text">Appointment History</span>
            </a>

        {{-- Doctor Links --}}
        @elseif($type === 'doctor')
            <a href="{{ route('redirect.by.role') }}" class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> <span class="sidebar-text">Patient Appointment</span>
            </a>
            <a href="{{ route('doctor.schedule') }}" class="{{ request()->routeIs('doctor.schedule') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span class="sidebar-text">My Schedule</span>
            </a>

         {{-- Admin Links --}}
        @elseif($type === 'admin')
            <a href="{{ route('redirect.by.role') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span class="sidebar-text">Home</span>
            </a>
            <a href="{{ route('admin.doctors.create') }}" class="{{ request()->routeIs('admin.doctors.create') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i> <span class="sidebar-text">Register Doctor</span>
            </a>
            <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.index') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> <span class="sidebar-text">Doctor's Appointments</span>
            </a>
            <a href="{{ route('laboratory.index') }}" class="{{ request()->routeIs('laboratory.index') ? 'active' : '' }}">
                <i class="bi bi-journal-medical"></i> <span class="sidebar-text">Laboratory Request</span>
            </a>
            <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients.index') ? 'active' : '' }}">
    <i class="bi bi-file-medical"></i> 
    <span class="sidebar-text">Patient Records</span>

</a>
<form action="{{ route('admin.appointments.triggerReminders') }}" method="POST">
    @csrf
    <button type="submit" >
        🔔 Reminders
    </button>
</form>

  @endif


        <!-- Settings -->
        <a class="dropdown-toggle" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="false" aria-controls="settingsMenu">
            <i class="bi bi-gear"></i>
            <span class="sidebar-text">Settings</span>
        </a>
        <div class="collapse ps-4" id="settingsMenu">
            <a href="{{ route('profile.edit') }}" class="d-block my-1"><i class="bi bi-pencil-square"></i> Edit Profile</a>
            <a href="{{ route('password.change') }}" class="d-block my-1"><i class="bi bi-key"></i> Change Password</a>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="my-1 w-100">
            @csrf
            <button type="submit" class="btn btn-link sidebar-link logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</div>
@endauth


<style>
/* Sidebar base */
.sidebar {
    position: fixed; top: 0; left: 0;
     /* 👈 push below header */
     height: calc(100vh - 70px); 
    width: 240px; height: 100vh;
    background: linear-gradient(180deg, #171a75, #2c2f9d);
    backdrop-filter: blur(10px);
    color: white;
    display: flex; flex-direction: column;
    
    box-shadow: 2px 0 12px rgba(0,0,0,0.2);
    z-index: 1000;
    overflow-y: auto;
    transition: width 0.3s ease, transform 0.3s ease;
    border-right: 1px solid rgba(255,255,255,0.1);
}

/* Sidebar links general hover */
.sidebar a, .sidebar button {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    width: 100%;
    gap: 12px;
    font-weight: 500;
    transition: background 0.25s ease, padding-left 0.25s ease, color 0.25s ease;
}

/* Hover effect for all links except logout and burger button */
.sidebar a:hover:not(.logout-btn),
.sidebar button:hover:not(.logout-btn):not(#burgerToggle) {
    background: rgba(255,255,255,0.15);
    padding-left: 25px;
    color: white;
    cursor: pointer;
}


/* Hover effect for logout button only */
.sidebar .logout-btn:hover {
    background: rgba(255, 0, 0, 0.15);
    color: #ff4d4d;
    padding-left: 25px;
    cursor: pointer;
}


/* Active link for all roles */
.sidebar a.active {
    background: rgba(255,255,255,0.25);
    border-left: 4px solid #ffd700;
    font-weight: 600;
}

/* Collapsed sidebar */
.sidebar.collapsed { width: 70px; align-items: center; padding-top: 15px; }
.sidebar.collapsed .sidebar-text, .sidebar.collapsed .sidebar-name, .sidebar.collapsed .sidebar-role { display: none; }
.sidebar.collapsed a, .sidebar.collapsed button { justify-content: center; padding: 14px 0; font-size: 18px; }
.sidebar.collapsed .sidebar-header img { width: 45px; height: 45px; border-width: 2px; }
.sidebar.collapsed #settingsMenu { display: none !important; }

/* Profile */
.sidebar-header .sidebar-img {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.85);
}
.sidebar-header img:hover { transform: scale(1.08); }

.burger-container {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    height: 40px;
    padding: 0 1rem 0.25rem 0;
    margin: 0;
}

.sidebar-header {
    padding: 0.5rem 1rem;
    margin: 0;
}


.burger-btn {
    
    border: none;
    cursor: pointer;
    
    display: flex;


}

.burger-btn i {
    transition: transform 0.3s ease;
    box-shadow: none;
}




/* Tooltips for collapsed sidebar */
.sidebar.collapsed a::after {
    content: attr(title);
    position: absolute;
    left: 75px;
    background: rgba(0,0,0,0.85);
    color: #fff;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    opacity: 0;
    white-space: nowrap;
    pointer-events: none;
    transform: translateY(-50%);
    top: 50%;
    transition: opacity 0.2s ease;
}
/* Profile info container */
.sidebar-user-info {
    display: flex;
    flex-direction: column;
}

/* When sidebar is collapsed, hide name and role */
.sidebar.collapsed .sidebar-user-info {
    display: none;
}

/* Adjust profile image alignment in collapsed mode */
.sidebar.collapsed .sidebar-header {
    justify-content: center;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const burgerToggle = document.getElementById('burgerToggle');
    const sidebar = document.getElementById('sidebar');
    const body = document.body;

    // Manual toggle
    burgerToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        body.classList.toggle('sidebar-open');
    });

    // Auto-collapse on small screens
    function handleResize() {
        if (window.innerWidth < 768) {
            sidebar.classList.add('collapsed');
            body.classList.remove('sidebar-open');
        } else {
            sidebar.classList.remove('collapsed');
        }
    }

    // Initial check
    handleResize();

    // Listen for screen resize
    window.addEventListener('resize', handleResize);
});
</script>

