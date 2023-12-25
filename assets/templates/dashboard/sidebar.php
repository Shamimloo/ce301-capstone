<!---------- Sidebar Begin ---------->
<div class="sidebar">
  <div class="sidebar-logo row mt-1">

    <div class="col-10 d-flex m-0">
      <img src="assets/images/site-logo.png" class="w-70 h-100" alt="Site logo">
    </div>
    <div class="col-2 sidebar-close px-1" id="sidebar-close">
      <i class="bx bx-left-arrow-alt px-2"></i>
    </div>
  </div>

  <!---------- Sidebar Wrap ---------->
  <div class="simlebar-sc" data-simplebar="init">
    <div class="simplebar-wrapper" style="margin: 0px;">
      <div class="simplebar-height-auto-observer-wrapper">
        <div class="simplebar-height-auto-observer"></div>
      </div>
      <div class="simplebar-mask">
        <div class="simplebar-offset" style="right: -18.75px; bottom: 0px;">
          <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
            <div class="simplebar-content px-4">
              <ul class="sidebar-menu sidebar-menu-container tf">

                <!---------- Dashboard ---------->
                <li>
                  <a href="<?php echo SITE_URL; ?>admin">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                  </a>
                </li>

                <!---------- Reports ---------->
                <!-- <li>
                  <a href="<?php echo SITE_URL; ?>report-summary">
                    <i class="fa-regular fa-clipboard"></i>
                    <span>Reports</span>
                  </a>
                </li> -->

                <!---------- Teacher Level ---------->
                <li class="sidebar-submenu" id="teacher-level">
                  <a href="javascript:void(0)" class="sidebar-menu-dropdown">
                    <i class="fa-solid fa-glasses"></i>
                    <span>Facilitators</span>
                    <div class="dropdown-icon">
                      <i class='bx bx-chevron-down'></i>
                    </div>
                  </a>
                  <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                      <a href="<?php echo SITE_URL; ?>facilitator-summary">
                        Manage Facilitators
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo SITE_URL; ?>facilitator-add">
                        Add Facilitators
                      </a>
                    </li>
                  </ul>
                </li>
               

                <!---------- Learner ---------->
                <li class="sidebar-submenu" id="student-level">
                  <a href="javascript:void(0)" class="sidebar-menu-dropdown">
                    <i class="fa-regular fa-user"></i>
                    <span>Learners</span>
                    <div class="dropdown-icon">
                      <i class='bx bx-chevron-down'></i>
                    </div>
                  </a>
                  <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                      <a href="<?php echo SITE_URL; ?>learner-summary">
                        Manage Learners
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo SITE_URL; ?>learner-add">
                        Add Learners
                      </a>
                    </li>
                  </ul>
                </li>

                <!---------- Learner Groups ---------->
                <li class="sidebar-submenu" id="student-house">
                  <a href="javascript:void(0)" class="sidebar-menu-dropdown">
                    <i class="fa-regular fa-flag"></i>
                    <span>Learner Groups</span>
                    <div class="dropdown-icon">
                      <i class='bx bx-chevron-down'></i>
                    </div>
                  </a>
                  <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                      <a href="<?php echo SITE_URL; ?>learnergroup-summary">
                        Manage Learner Groups
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo SITE_URL; ?>learnergroup-add">
                        Add Learner Groups
                      </a>
                    </li>
                  </ul>
                </li>

                <!---------- Info Page ---------->
                <li class="sidebar-submenu" id="info">
                  <a href="javascript:void(0)" class="sidebar-menu-dropdown">
                    <i class="fa-solid fa-info"></i>
                    <span>Info Pages</span>
                    <div class="dropdown-icon">
                      <i class='bx bx-chevron-down'></i>
                    </div>
                  </a>
                  <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                      <a href="<?php echo SITE_URL; ?>infopage-summary">
                        Manage Pages
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo SITE_URL; ?>infopage-add">
                        Add Page
                      </a>
                    </li>
                  </ul>
                </li>

                
                <!---------- Categories ---------->
                <li class="sidebar-submenu" id="categories">
                  <a href="javascript:void(0)" class="sidebar-menu-dropdown">
                    <!-- <i class="fa-regular fa-file-lines"></i> -->
                    <i class="fa-regular fa-rectangle-list"></i>
                    <span>Categories</span>
                    <div class="dropdown-icon">
                      <i class='bx bx-chevron-down'></i>
                    </div>
                  </a>
                  <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                      <a href="<?php echo SITE_URL; ?>category-summary">
                        Manage Categories
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo SITE_URL; ?>category-add">
                        Add Categories
                      </a>
                    </li>
                  </ul>
                </li>
                

                <!---------- Questions ---------->
                <li class="sidebar-submenu" id="questions">
                  <a href="javascript:void(0)" class="sidebar-menu-dropdown">
                    <i class="fa-regular fa-circle-question"></i>
                    <span>Questions</span>
                    <div class="dropdown-icon">
                      <i class='bx bx-chevron-down'></i>
                    </div>
                  </a>
                  <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                      <a href="<?php echo SITE_URL; ?>question-summary">
                        Manage Questions
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo SITE_URL; ?>question-add">
                        Add Questions
                      </a>
                    </li>
                  </ul>
                </li>

                <!---------- Quiz ---------->
                <li class="sidebar-submenu" id="quiz">
                  <a href="javascript:void(0)" class="sidebar-menu-dropdown">
                    <i class="fa-regular fa-lightbulb"></i>
                    <span>Quiz</span>
                    <div class="dropdown-icon">
                      <i class='bx bx-chevron-down'></i>
                    </div>
                  </a>
                  <ul class="sidebar-menu sidebar-menu-dropdown-content">
                    <li>
                      <a href="<?php echo SITE_URL; ?>quiz-summary">
                        Manage Quizzes
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo SITE_URL; ?>quiz-add">
                        Add Quiz
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="simplebar-placeholder" style="width: auto; height: 866px;"></div>
    </div>
    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
      <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
    </div>
    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
      <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: block; height: 803px;"></div>
    </div>
  </div>
</div>