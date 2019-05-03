<header class="primary">
  <div class="firstbar">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-12">
          {{-- <div class="brand">
            <a href="index.html">
              <img src="layouts/images/logo.png" alt="Magz Logo">
            </a>
          </div> --}}
        </div>
        <div class="col-md-6 col-sm-12" style="width: 60%">
          {{-- <form class="search" autocomplete="off">
            <div class="form-group">
              <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Type something here">
                <div class="input-group-btn">
                  <button class="btn btn-primary"><i class="ion-search"></i></button>
                </div>
              </div>
            </div>
            <div class="help-block">
              <div>Popular:</div>
              <ul>
                <li><a href="#">HTML5</a></li>
                <li><a href="#">CSS3</a></li>
                <li><a href="#">Bootstrap 3</a></li>
                <li><a href="#">jQuery</a></li>
                <li><a href="#">AnguarJS</a></li>
              </ul>
            </div>
          </form> --}}
          <h2 style="float: center; font-family: Tahoma Geneva">THỐNG KÊ TAI NẠN GIAO THÔNG TẠI VIỆT NAM</h2>
        </div>
        {{-- <div class="col-md-3 col-sm-12 text-right">
          <ul class="nav-icons">
            <li><a href="register.html"><i class="ion-person-add"></i><div>Register</div></a></li>
            <li><a href="login.html"><i class="ion-person"></i><div>Login</div></a></li>
          </ul>
        </div> --}}
      </div>
    </div>
  </div>

  <!-- Start nav -->
  <nav class="menu">
    <div class="container">
      {{-- <div class="brand">
        <a href="#">
          <img src="images/logo.png" alt="Magz Logo">
        </a>
      </div> --}}
      <div class="mobile-toggle">
        <a href="#" data-toggle="menu" data-target="#menu-list"><i class="ion-navicon-round"></i></a>
      </div>
      <div class="mobile-toggle">
        <a href="#" data-toggle="sidebar" data-target="#sidebar"><i class="ion-ios-arrow-left"></i></a>
      </div>
      <div id="menu-list">
        <ul class="nav-list">
          <li class="for-tablet nav-title"><a>Menu</a></li>
          <li class="for-tablet"><a href="login.html">Login</a></li>
          <li class="for-tablet"><a href="register.html">Register</a></li>
          <li><a href="{{url("/")}}">Những vụ tai nạn đã diễn ra</a></li>
          <li class="">
            <a href="{{url('/thong-ke-thang')}}">Thống kê theo tháng<i class=""></i></a>
          </li>
          {{-- <li class="dropdown magz-dropdown"><a href="#">Học qua Video<i class="ion-ios-arrow-right"></i></a>
            <ul class="dropdown-menu">
              <li class="dropdown magz-dropdown"><a href="category.html">Tiếng Nhật sơ cấp <i class="ion-ios-arrow-right"></i></a>
                <ul class="dropdown-menu">
                  <li><a href="{{url('/video/tiengnhatsocap/ShinNihongoNoKiso')}}">Shin Nihongo no Kiso</a></li>
                  <li class="dropdown magz-dropdown"><a href="{{url('/video/tiengnhatsocap/giaotiepcoban')}}">Tiếng Nhật giao tiếp cơ bản <i class="ion-ios-arrow-right"></i></a>
                  </li>
                  <li><a href="{{url('/video/tiengnhatsocap/KanjiLookandLearn')}}">512 Kanji Look and Learn</a>
                </ul>
              </li>
              <li class="dropdown magz-dropdown"><a href="category.html">Học JLPT N5 <i class="ion-ios-arrow-right"></i></a>
                <ul class="dropdown-menu">
                  <li><a href="category.html">Shin Nihongo no Kiso</a></li>
                  <li class="dropdown magz-dropdown"><a href="category.html">Tiếng Nhật giao tiếp cơ bản <i class="ion-ios-arrow-right"></i></a>
                  </li>
                  <li><a href="category.html">512 Kanji Look and Learn</a>
                </ul>
              </li>
              <li class="dropdown magz-dropdown"><a href="category.html">Học JLPT N4 <i class="ion-ios-arrow-right"></i></a>
                <ul class="dropdown-menu">
                  <li><a href="category.html">Shin Nihongo no Kiso</a></li>
                  <li class="dropdown magz-dropdown"><a href="category.html">Tiếng Nhật giao tiếp cơ bản <i class="ion-ios-arrow-right"></i></a>
                  </li>
                  <li><a href="category.html">512 Kanji Look and Learn</a>
                </ul>
              </li>
              <li class="dropdown magz-dropdown"><a href="category.html">Học JLPT N3 <i class="ion-ios-arrow-right"></i></a>
                <ul class="dropdown-menu">
                  <li><a href="category.html">Shin Nihongo no Kiso</a></li>
                  <li class="dropdown magz-dropdown"><a href="category.html">Tiếng Nhật giao tiếp cơ bản <i class="ion-ios-arrow-right"></i></a>
                  </li>
                  <li><a href="category.html">512 Kanji Look and Learn</a>
                </ul>
              </li>
              <li class="dropdown magz-dropdown"><a href="category.html">Học JLPT N2 <i class="ion-ios-arrow-right"></i></a>
                <ul class="dropdown-menu">
                  <li><a href="category.html">Shin Nihongo no Kiso</a></li>
                  <li class="dropdown magz-dropdown"><a href="category.html">Tiếng Nhật giao tiếp cơ bản <i class="ion-ios-arrow-right"></i></a>
                  </li>
                  <li><a href="category.html">512 Kanji Look and Learn</a>
                </ul>
              </li>
              <li class="dropdown magz-dropdown"><a href="category.html">Học JLPT N1 <i class="ion-ios-arrow-right"></i></a>
                <ul class="dropdown-menu">
                  <li><a href="category.html">Shin Nihongo no Kiso</a></li>
                  <li class="dropdown magz-dropdown"><a href="category.html">Tiếng Nhật giao tiếp cơ bản <i class="ion-ios-arrow-right"></i></a>
                  </li>
                  <li><a href="category.html">512 Kanji Look and Learn</a>
                </ul>
              </li>
            </ul>
          </li> --}}
        </ul>
      </div>
    </div>
  </nav>
  <!-- End nav -->
</header>
