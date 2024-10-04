<div class="header">
    <div class="header-left">
        <a href="{{ route(Auth::user()->role . ".dashboard")}}" class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
        </a>
        <a href="{{ route(Auth::user()->role . ".dashboard")}}" class="logo logo-small">
            <img src="{{ asset('assets/img/logo-small.png') }}" alt="Logo" width="30" height="30">
        </a>
    </div>
    <div class="menu-toggle">
        <a href="javascript:void(0);" id="toggle_btn">
            <i class="fas fa-bars"></i>
        </a>
    </div>

    <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
    </a>

    <ul class="nav user-menu">
        <li class="nav-item dropdown noti-dropdown me-2">
            <a href="#" class="dropdown-toggle nav-link header-nav-list" data-bs-toggle="dropdown" id="notification-dropdown">
                <img src="{{ asset('assets/img/icons/header-icon-05.svg') }}" alt="">
            </a>
            <div class="dropdown-menu notifications" id="notification-content">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                </div>
                <div class="noti-content">
                    <ul class="notification-list" id="notification-list">
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                </div>
            </div>
        </li>

        <li class="nav-item zoom-screen me-2">
            <a href="#" class="nav-link header-nav-list win-maximize">
                <img src="{{ asset('assets/img/icons/header-icon-04.svg') }}" alt="">
            </a>
        </li>

        <li class="nav-item dropdown has-arrow new-user-menus">
            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img">
                    <img class="rounded-circle" src="{{ asset('assets/img/profiles/avatar-01.jpg') }}" width="31" alt="{{ Auth::user()->name }}">
                    <div class="user-text">
                        <h6>{{ Auth::user()->name }}</h6>
                        <p class="text-muted mb-0">{{ Auth::user()->role }}</p>
                    </div>
                </span>
            </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="{{ asset('assets/img/profiles/avatar-01.jpg') }}" alt="User Image" class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6>{{ Auth::user()->name }}</h6>
                        <p class="text-muted mb-0">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <a class="dropdown-item" href="{{ route('auth.logout') }}">Logout</a>
            </div>
        </li>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#notification-dropdown').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route("notifications.get") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#notification-list').empty();
                    if (data.length === 0) {
                        $('#notification-list').append('<li class="notification-message">No notifications available.</li>');
                    } else {
                        $.each(data, function(index, notification) {
                            $('#notification-list').append(`
                                <li class="notification-message">
                                    <a href="##">
                                        <div class="media d-flex">
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">${notification.message}</span></p>
                                                <p class="noti-time"><span class="notification-time">${new Date(notification.created_at).toLocaleString()}</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            `);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Could not fetch notifications.');
                }
            });
        });

        $('#clear-notifications').on('click', function() {
            alert('Clear all notifications action can be implemented here.');
        });
    });
</script>
