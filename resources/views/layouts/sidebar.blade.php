<div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
            <img src="../assets/images/logo/logo.jpg" alt="Logo" srcset="" style="width: 70%;margin-top: 25px;margin-left: 12px;margin-bottom: -36px;">

                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <!-- <a href="index.html"><img src="assets/images/logo/logo.jpg" alt="Logo" srcset=""></a> -->
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>

                    </div>
                </div>



                <div class="recent-message d-flex px-4 py-3">
                    <div class="avatar avatar-lg">
                        <img src="../assets/images/faces/4.jpg">
                    </div>
                    <div class="name ms-4">
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <h6 class="text-muted mb-0">{{ auth()->user()->designation }}</h6>
                        <!-- <span>{{ auth()->user()->empID }}</span> -->
                    </div>

                </div>

                <div class="sidebar-menu">

                    <ul class="menu">

                        <!-- <li class="sidebar-item  dashboard_m">
                            <a href="index" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li> -->

                        @if(auth()->user()->role_type =='super_admin')
                        <li class="sidebar-item  recruit_req_m">
                            <a href="view_recruit_request_default" class='sidebar-link'>
                                <i class="bi bi-pen-fill"></i>
                                <span>Allocation List</span>
                            </a>
                        </li>
                        <li class="sidebar-item candidate_profile_m">
                            <a href="candidate_profile" class='sidebar-link'>
                                <i class="bi bi-file-person"></i>
                                <span>Candidate Database</span>
                            </a>
                        </li>

                        <li class="sidebar-item external_candidate_database_m">
                            <a href="external_candidate_database" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>External Candidate Database</span>
                            </a>
                        </li>

                        <li class="sidebar-item ticket_report_m">
                            <a href="ticket_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Allocation Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item recruiter_report_m">
                            <a href="recruiter_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Recruiter Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item doc_collection_bc_m">
                            <a href="document_collection_bc" class='sidebar-link'>
                                <i class="bi bi-card-list"></i>
                                <span>Document Collection</span>
                            </a>
                        </li>
                        <li class="sidebar-item offers_bc_m">
                            <a href="offers_bc" class='sidebar-link'>
                                <i class="bi bi-card-list"></i>
                                <span>Offers</span>
                            </a>
                        </li>
                        <li class="sidebar-item view_recruiter_m">
                            <a href="view_recruiter" class='sidebar-link'>
                                <i class="bi bi-person-plus-fill"></i>
                                <span>User List</span>
                            </a>
                        </li>
                        <li class="sidebar-item deleted_request_m">
                            <a href="deleted_request" class='sidebar-link'>
                                <i class="bi bi-trash"></i>
                                <span>Deleted Request</span>
                            </a>
                        </li>
                        <li class="sidebar-item daily_report_m">
                            <a href="daily_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        {{-- <li class="sidebar-item score_card_m">
                            <a href="score_card" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Score Card</span>
                            </a>
                        </li>
                         <li class="sidebar-item recruiter_daily_report_m">
                            <a href="recruiter_daily_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Recruiter Daily Report</span>
                            </a>
                        </li> --}}
                        <!-- <li class="sidebar-item prohire_card_m">
                            <a href="prohire_card" class='sidebar-link'>
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>ProHire Card</span>
                            </a>
                        </li> -->

                        @elseif(auth()->user()->role_type =='virtual_audit')

                        <li class="sidebar-item candidate_profile_m">
                            <a href="candidate_profile" class='sidebar-link'>
                                <i class="bi bi-file-person"></i>
                                <span>Candidate Database</span>
                            </a>
                        </li>
                        <li class="sidebar-item ticket_report_m">
                            <a href="ticket_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Allocation Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item recruiter_report_m">
                            <a href="recruiter_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Recruiter Report</span>
                            </a>
                        </li>

                        <!-- <li class="sidebar-item prohire_card_m">
                            <a href="prohire_card" class='sidebar-link'>
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>ProHire Card</span>
                            </a>
                        </li> -->
                        @elseif(auth()->user()->role_type =='leader')
                        <li class="sidebar-item ol_leader_verify_m">
                            <a href="ol_leader_verify" class='sidebar-link'>
                                <i class="bi bi-card-list"></i>
                                <span>Offers </span>
                            </a>
                        </li>
                        <li class="sidebar-item candidate_profile_m">
                            <a href="candidate_profile" class='sidebar-link'>
                                <i class="bi bi-file-person"></i>
                                <span>Candidate Database</span>
                            </a>
                        </li>
                        <li class="sidebar-item ticket_report_m">
                            <a href="ticket_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Allocation Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item recruiter_report_m">
                            <a href="recruiter_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Recruiter Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item daily_report_m">
                            <a href="daily_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Daily Report</span>
                            </a>
                        </li>
                        <!-- <li class="sidebar-item prohire_card_m">
                            <a href="prohire_card" class='sidebar-link'>
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>ProHire Card</span>
                            </a>
                        </li> -->
                        @elseif(auth()->user()->role_type =='backend_coordinator')
                        <li class="sidebar-item  recruit_req_m">
                            <a href="view_recruit_request_default" class='sidebar-link'>
                                <i class="bi bi-pen-fill"></i>
                                <span>Allocation List</span>
                            </a>
                        </li>
                        <li class="sidebar-item candidate_profile_m">
                            <a href="candidate_profile" class='sidebar-link'>
                                <i class="bi bi-file-person"></i>
                                <span>Candidate Database</span>
                            </a>
                        </li>
                        <li class="sidebar-item external_candidate_database_m">
                            <a href="external_candidate_database" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>External Candidate Database</span>
                            </a>
                        </li>
                        <li class="sidebar-item ticket_report_m">
                            <a href="ticket_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Allocation Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item recruiter_report_m">
                            <a href="recruiter_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Recruiter Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item offers_bc_m">
                            <a href="offers_bc" class='sidebar-link'>
                                <i class="bi bi-card-list"></i>
                                <span>Offers</span>
                            </a>
                        </li>
                        <li class="sidebar-item daily_report_m">
                            <a href="daily_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        {{-- <li class="sidebar-item score_card_m">
                            <a href="score_card" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Score Card</span>
                            </a>
                        </li>
                         <li class="sidebar-item recruiter_daily_report_m">
                            <a href="recruiter_daily_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Recruiter Daily Report</span>
                            </a>
                        </li> --}}
                        <!-- <li class="sidebar-item prohire_card_m">
                            <a href="prohire_card" class='sidebar-link'>
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>ProHire Card</span>
                            </a>
                        </li> -->
                        @elseif(auth()->user()->role_type =='approver')
                        <li class="sidebar-item ol_leader_verify_m">
                            <a href="ol_leader_verify" class='sidebar-link'>
                                <i class="bi bi-card-list"></i>
                                <span>Offers </span>
                            </a>
                        </li>
                        <li class="sidebar-item candidate_profile_m">
                            <a href="candidate_profile" class='sidebar-link'>
                                <i class="bi bi-file-person"></i>
                                <span>Candidate Database</span>
                            </a>
                        </li>
                        <li class="sidebar-item external_candidate_database_m">
                            <a href="external_candidate_database" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>External Candidate Database</span>
                            </a>
                        </li>
                        <li class="sidebar-item ticket_report_m">
                            <a href="ticket_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Allocation Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item recruiter_report_m">
                            <a href="recruiter_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Recruiter Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item daily_report_m">
                            <a href="daily_report" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>Daily Report</span>
                            </a>
                        </li>
                        @elseif(auth()->user()->role_type =='payroll')
                        <li class="sidebar-item ol_payroll_verify_m">
                            <a href="ol_payroll_verify" class='sidebar-link'>
                                <i class="bi bi-card-list"></i>
                                <span>Offers </span>
                            </a>
                        </li>
                        @elseif(auth()->user()->role_type =='finance')
                            <li class="sidebar-item offers_finance_m">
                                <a href="offers_finance" class='sidebar-link'>
                                    <i class="bi bi-card-list"></i>
                                    <span>PO Request</span>
                                </a>
                            </li>
                            @elseif(auth()->user()->role_type =='po_team')
                            <li class="sidebar-item offers_poteam_m">
                                <a href="offers_poteam" class='sidebar-link'>
                                    <i class="bi bi-card-list"></i>
                                    <span>PO Request</span>
                                </a>
                            </li>
                        @else
                        <li class="sidebar-item  task_detail_m">
                            <a href="view_task_detail" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Allocation List</span>
                            </a>
                        </li>
                        <li class="sidebar-item candidate_profile_m">
                            <a href="list_candidate_profile" class='sidebar-link'>
                                <i class="bi bi-file-person"></i>
                                <span>Candidate Database</span>
                            </a>
                        </li>
                        <li class="sidebar-item ticket_report_m">
                            <a href="ticket_report_recruiter" class='sidebar-link'>
                                <i class="bi bi-server"></i>
                                <span>My Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item doc_collection_m">
                            <a href="document_collection" class='sidebar-link'>
                                <i class="bi bi-card-list"></i>
                                <span>Document Collection</span>
                            </a>
                        </li>
                        <li class="sidebar-item offers_m">
                            <a href="offers_recruiter" class='sidebar-link'>
                                <i class="bi bi-card-list"></i>
                                <span>Offers</span>
                            </a>
                        </li>

                        @endif

                        <li class="sidebar-item change_password_m">
                            <a href="change_password" class='sidebar-link'>
                                <i class="bi bi-lock-fill"></i>
                                <span>Change Password</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../" class='sidebar-link'>
                                <i class="bi bi-door-closed-fill"></i>
                                <span>Logout</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
