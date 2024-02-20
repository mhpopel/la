<div class="side-menu animate-dropdown outer-bottom-xs">
    <div class="head"><i class="icon fa fa-align-justify fa-fw"></i> Categories</div>
    <nav class="yamm megamenu-horizontal">
      <ul class="nav">
        <li class="dropdown menu-item">
            <a href="{{ url('get/all-products') }}"><i class="icon " aria-hidden="true"></i>ALL PRODUCTS</a>
          <!-- /.dropdown-menu -->
        </li>
        @foreach($categories as $category)
        <li class="dropdown menu-item">
            <a href="{{ url('category/product/'.$category->id.'/'.$category->category_slug_en) }}" ><i class="icon {{ $category->category_icon }}" aria-hidden="true"></i>{{ $category->category_name_en }}</a>
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
