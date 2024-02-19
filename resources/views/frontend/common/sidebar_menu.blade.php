<div class="side-menu animate-dropdown outer-bottom-xs">
    <div class="head"><i class="icon fa fa-align-justify fa-fw"></i> Categories</div>
    <nav class="yamm megamenu-horizontal">
      <ul class="nav">

        @foreach($categories as $category)
        <li class="dropdown menu-item">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon {{ $category->category_icon }}" aria-hidden="true"></i>{{ $category->category_name_en }}</a>
          <!-- /.dropdown-menu -->
        </li>
        @endforeach
        <!-- /.menu-item -->

        <!-- /.menu-item -->

      </ul>
      <!-- /.nav -->
    </nav>
    <!-- /.megamenu-horizontal -->
  </div>
