<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>
                <li class="submenu active">
                    <a href="{{ route(Auth::user()->role . ".dashboard")}}"><i class="feather-grid"></i> <span> Dashboard</span></a>
                </li>

                @can('viewAny', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fa fa-user-secret"></i>
                            <span> Admin</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{route("admin.index")}}">Admin List</a></li>
                            <li><a href="{{route("admin.create")}}">Admin Add</a></li>
                        </ul>
                    </li>
                @endcan

                @can('viewAny', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-users"></i> <span> Parents</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("parent.index") }}">Parent List</a></li>
                            <li><a href="{{ route("parent.create") }}">Parent Add</a></li>
                        </ul>
                    </li>
                @endcan

                @can('viewAny', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-graduation-cap"></i> <span> Students</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("student.index") }}">Student List</a></li>
                            @can('viewAny', Auth::user())
                                <li><a href="{{ route("student.create") }}">Student Add</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('viewAny', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-chalkboard-teacher"></i> <span> Teachers</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("teacher.index") }}">Teacher List</a></li>
                            <li><a href="{{ route("teacher.create") }}">Teacher Add</a></li>
                        </ul>
                    </li>
                @endcan

                @can('viewAny', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-book-reader"></i> <span> Subjects</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("subject.index") }}">Subject List</a></li>
                            <li><a href="{{ route("subject.create") }}">Subject Add</a></li>
                        </ul>
                    </li>
                @endcan

                @cannot("viewParent", Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-book-open"></i> <span> Courses</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("course.index") }}">Courses List</a></li>
                            @can('view', Auth::user())
                                <li><a href="{{ route("course.create") }}">Courses Add</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcannot

                @can("viewParent", Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-child"></i> <span>Children </span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("parent.children") }}">Children List</a></li>
                        </ul>
                    </li>
                @endcan

                @can('viewAny', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-registered"></i> <span> Enrollments</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("enrollment.index") }}">Enrollment List</a></li>
                            <li><a href="{{ route("enrollment.create") }}">Enrolments Add</a></li>
                        </ul>
                    </li>
                @endcan

                @cannot("view", Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-comment-dollar"></i> <span> Fees</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("fees.index") }}">Fees List</a></li>
                            @can('viewAny', Auth::user())
                                <li><a href="{{ route("fees.create") }}">Fees Add</a></li>
                            @endcan
                        </ul>
                    </li>
                @elsecan('viewAny', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-comment-dollar"></i> <span> Fees</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("fees.index") }}">Fees List</a></li>
                            @can('viewAny', Auth::user())
                                <li><a href="{{ route("fees.create") }}">Fees Add</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcannot

                <li class="submenu">
                    <a href="#"><i class="fas fa-comment-dots"></i> <span> documents</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route("documents.index") }}"> documents List</a></li>
                        <li><a href="{{ route("documents.create") }}"> documents Add</a></li>
                    </ul>
                </li>


                @can('view', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span> salaries</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("salary.index") }}">salaries List</a></li>
                            @can('viewAny', Auth::user())
                                <li><a href="{{ route("salary.create") }}">salaries Add</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('view', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-comment-dots"></i> <span> Messages</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("messages.index") }}"> Messages List</a></li>
                        </ul>
                    </li>
                @endcan

            </ul>
        </div>
    </div>
</div>
