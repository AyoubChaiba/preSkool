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


                @can("viewAny", Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-book-open"></i>
                            <span> Classes</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{route("class.index")}}">Manage Classes</a></li>
                            <li><a href="{{route("section.index")}}">Manage Sections</a></li>
                        </ul>
                    </li>
                @endcan

                @can("viewAny", Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-book-reader"></i>
                            <span> Subjects</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @if (classGet()->isEmpty())
                                <li><a href="#">{{ __('No classes available') }}</a></li>
                            @endif
                            @foreach ( classGet() as $class )
                                <li><a href="{{route('subject.index', $class->id)}}">{{ $class->class_name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endcan

                @can("viewAny", Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-users"></i> <span> Parents</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("parent.index") }}">Parent List</a></li>
                            <li><a href="{{ route("parent.create") }}">Parent Add</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="fas fa-chalkboard-teacher"></i> <span> Teachers</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("teacher.index") }}">Teacher List</a></li>
                            <li><a href="{{ route("teacher.create") }}">Teacher Add</a></li>
                        </ul>
                    </li>
                @endcan

                @can("viewParent", Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-graduation-cap"></i> <span> Students</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("student.index") }}">Student List</a></li>
                            @can("viewAny", Auth::user())
                                <li><a href="{{ route("student.create") }}">Student Add</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <li class="submenu">
                    <a href="#"><i class="fas fa-table"></i>
                        <span> Time tables</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if (classGet()->isEmpty())
                            <li><a href="#">{{ __('No classes available') }}</a></li>
                        @endif
                        @foreach ( classGet() as $class )
                            <li><a href="{{route('timetables.index', $class->id)}}">{{ $class->class_name }}</a></li>
                        @endforeach
                    </ul>
                </li>

                @if(Auth::user()->role === "student")
                    <li class="submenu">
                        <a href="#"><i class="fas fa-graduation-cap"></i>
                            <span> Students</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{route('attendance.show', Auth::user()->student->id)}}"> Attendance</a></li>
                            <li><a href="{{route('student.grades', Auth::user()->student->id)}}"> Marksheet</a></li>
                            <li><a href="{{route('exam.results')}}"> Exam mark</a></li>
                        </ul>
                    </li>
                @endcan

                @can('view', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fa fa-tasks"></i>
                            <span> Attendance</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @if (classGet()->isEmpty())
                                <li><a href="#">{{ __('No classes available') }}</a></li>
                            @endif
                            @foreach ( classGet() as $class )
                                <li><a href="{{route('attendance.index', $class->id)}}">{{ $class->class_name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endcan


                <li class="submenu">
                    <a href="#"><i class="fas fa-clipboard"></i>
                        <span> exams</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if (classGet()->isEmpty())
                            <li><a href="#">{{ __('No classes available') }}</a></li>
                        @endif
                        @foreach ( classGet() as $class )
                            <li><a href="{{route('exam.index', $class->id)}}">{{ $class->class_name }}</a></li>
                        @endforeach
                    </ul>
                </li>

                @can('viewAny', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fa fa-tag"></i>
                            <span> Grade points</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route("gradepoints.index") }}">Grade Points List</a></li>
                            <li><a href="{{ route("gradepoints.create") }}">Grade Point Add</a></li>
                        </ul>
                    </li>
                @endcan


                @can('view', Auth::user())
                    <li class="submenu">
                        <a href="#"><i data-feather="award"></i>
                            <span> Exam marks</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @if (classGet()->isEmpty())
                                <li><a href="#">{{ __('No classes available') }}</a></li>
                            @endif
                            @foreach ( classGet() as $class )
                                <li><a href="{{route('grade.index', $class->id)}}">{{ $class->class_name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endcan


                @can("viewStudent", Auth::user())
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
                @endcan

                @if(Auth::user()->role === "parent")
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
                @endif



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

                <li >
                    <a href="{{route("conversations.index")}}"><i class="fa fa-comment"></i>
                        <span> Messages</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
