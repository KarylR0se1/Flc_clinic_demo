<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ config('app.name', 'FLC Clinic Appointments & Records Management System') }}</title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- FullCalendar -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <style>
    .dropdown-item { white-space: normal; word-break: break-word; }
    .notification-badge { font-size: 0.6rem; top: 0.2rem; right: 0.2rem; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- Header -->
<header id="mainHeader" class="bg-white shadow-sm py-3 sticky top-0 z-10 ml-[240px]">
  <div class="container mx-auto px-6 flex justify-between items-center">

    <!-- Title -->
    <span class=" text-blue-800 text-lg md:text-xl font-bold ">
      FLC Clinic Appointments and Records Management System
    </span>

    <!-- Notification Bell -->
    @auth
      <div class="dropdown ms-auto">
        <a class="nav-link position-relative" href="#" role="button" id="notificationDropdown"
           data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell-fill fs-4 fw-bold text-warning"></i>
          @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ Auth::user()->unreadNotifications->count() }}
            </span>
          @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-end p-2 shadow"
            aria-labelledby="notificationDropdown" style="width: 300px;">
          <li class="dropdown-header fw-bold">Notifications</li>
          @forelse(Auth::user()->unreadNotifications as $notification)
            <li class="dropdown-item">
              <a href="{{ route('appointments.show', $notification->data['appointment_id']) }}" 
                 class="text-decoration-none">
                {{ $notification->data['message'] }}
              </a>
            </li>
          @empty
            <li class="dropdown-item text-muted">No new notifications</li>
          @endforelse
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
              @csrf
              <button class="btn btn-sm btn-primary w-100">Mark all as read</button>
            </form>
          </li>
        </ul>
      </div>
    @endauth
  </div>
</header>

  <!-- Main -->
  <main class="container px-4 py-6 flex flex-col sm:flex-row gap-4">
    
    <!-- Sidebar -->
    <nav class="w-full sm:w-1/4 ">
      <x-sidebar :type="auth()->user()?->role ?? 'guest'" />
    </nav>

    <!-- Content -->
    <section class="w-full sm:w-3/4">
      @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error) 
              <li>{{ $error }}</li> 
            @endforeach
          </ul>
        </div>
      @endif

      <div class="content">
        @yield('content')
      </div>
    </section>
  </main>

  <!-- Notification Polling -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const bell = document.querySelector('#notificationDropdown');
      if (!bell) return;

      const dropdownMenu = bell.nextElementSibling;
      const badge = bell.querySelector('.badge');

      async function fetchNotifications() {
        try {
          const res = await fetch('{{ route('notifications.fetchUnread') }}');
          const data = await res.json();

          if (badge) {
            if (data.count > 0) {
              badge.textContent = data.count;
              badge.classList.remove('d-none');
            } else {
              badge.classList.add('d-none');
            }
          }

          if (dropdownMenu) {
            dropdownMenu.innerHTML = `
              <li class="dropdown-header fw-bold">Notifications</li>
              ${data.notifications.length > 0
                ? data.notifications.map(n => `
                  <li class="dropdown-item">
                    <a href="/appointments/${n.appointment_id}" class="text-decoration-none">
                      ${n.message}
                    </a>
                  </li>`).join('')
                : '<li class="dropdown-item text-muted">No new notifications</li>'
              }
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                  @csrf
                  <button class="btn btn-sm btn-primary w-100">Mark all as read</button>
                </form>
              </li>
            `;
          }
        } catch(err) {
          console.error('Error fetching notifications:', err);
        }
      }

      fetchNotifications();
      setInterval(fetchNotifications, 15000);
    });

document.addEventListener('DOMContentLoaded', () => {
    const burgerToggle = document.getElementById('burgerToggle');
    const sidebar = document.getElementById('sidebar');
    const header = document.getElementById('mainHeader');

    function updateHeaderMargin() {
        if (sidebar.classList.contains('collapsed')) {
            header.style.marginLeft = '70px';
        } else {
            header.style.marginLeft = '240px';
        }
    }

    // Toggle sidebar and update header margin
    burgerToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        document.body.classList.toggle('sidebar-open');
        updateHeaderMargin();
    });

    // Handle screen resize
    function handleResize() {
        if (window.innerWidth < 768) {
            sidebar.classList.add('collapsed');
            document.body.classList.remove('sidebar-open');
        } else {
            sidebar.classList.remove('collapsed');
        }
        updateHeaderMargin();
    }

    // Initial setup
    handleResize();
    updateHeaderMargin();

    window.addEventListener('resize', handleResize);
});
</script>

</body>
</html>
