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
                        <a href="#"><i class="fa fa-user-secret"></i> <span> Admin</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{route("admin.index")}}">Admin List</a></li>
                            <li><a href="{{route("admin.create")}}">Admin Add</a></li>
                        </ul>
                    </li>
                @endcan

                @can('view', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-users"></i> <span> Parents</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("parent.index") }}">Parent List</a></li>
                            @can('viewAny', Auth::user())
                                <li><a href="{{ route("parent.create") }}">Parent Add</a></li>
                            @endcan
                        </ul>
                    </li>
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

                @can('view', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-chalkboard-teacher"></i> <span> Teachers</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("teacher.index") }}">Teacher List</a></li>
                            @can('viewAny', Auth::user())
                                <li><a href="{{ route("teacher.create") }}">Teacher Add</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('view', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-book-reader"></i> <span> Subjects</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("subject.index") }}">Subject List</a></li>
                            <li><a href="{{ route("subject.create") }}">Subject Add</a></li>
                        </ul>
                    </li>
                @endcan

                @can('view', Auth::user())
                    <li class="submenu">
                        <a href="#"><i class="fas fa-building"></i> <span> Courses</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route("course.index") }}">Courses List</a></li>
                            <li><a href="{{ route("course.create") }}">Courses Add</a></li>
                        </ul>
                    </li>
                @endcan

                <li class="submenu">
                    <a href="#"><i class="fas fa-clipboard"></i> <span> Invoices</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="invoices.html">Invoices List</a></li>
                        <li><a href="invoice-grid.html">Invoices Grid</a></li>
                        <li><a href="add-invoice.html">Add Invoices</a></li>
                        <li><a href="edit-invoice.html">Edit Invoices</a></li>
                        <li><a href="view-invoice.html">Invoices Details</a></li>
                        <li><a href="invoices-settings.html">Invoices Settings</a></li>
                    </ul>
                </li>
                <li class="menu-title">
                    <span>Management</span>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span> Accounts</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="fees-collections.html">Fees Collection</a></li>
                        <li><a href="expenses.html">Expenses</a></li>
                        <li><a href="salary.html">Salary</a></li>
                        <li><a href="add-fees-collection.html">Add Fees</a></li>
                        <li><a href="add-expenses.html">Add Expenses</a></li>
                        <li><a href="add-salary.html">Add Salary</a></li>
                    </ul>
                </li>
                <li>
                    <a href="holiday.html"><i class="fas fa-holly-berry"></i> <span>Holiday</span></a>
                </li>
                <li>
                    <a href="fees.html"><i class="fas fa-comment-dollar"></i> <span>Fees</span></a>
                </li>
                <li>
                    <a href="exam.html"><i class="fas fa-clipboard-list"></i> <span>Exam list</span></a>
                </li>
                <li>
                    <a href="event.html"><i class="fas fa-calendar-day"></i> <span>Events</span></a>
                </li>
                <li>
                    <a href="time-table.html"><i class="fas fa-table"></i> <span>Time Table</span></a>
                </li>
                <li>
                    <a href="library.html"><i class="fas fa-book"></i> <span>Library</span></a>
                </li>
                <li class="submenu">
                    <a href="#"><i class="fa fa-newspaper"></i> <span> Blogs</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="blog.html">All Blogs</a></li>
                        <li><a href="add-blog.html">Add Blog</a></li>
                        <li><a href="edit-blog.html">Edit Blog</a></li>
                    </ul>
                </li>
                <li>
                    <a href="settings.html"><i class="fas fa-cog"></i> <span>Settings</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
