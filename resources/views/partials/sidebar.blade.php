<div id="sidebarAdmin" class="col-md-2 fadeContent">
    <ul class="nav flex-column">
        @can('view', App\Type::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('type_index') }}">Type</a>
        </li>
        @endcan
        @can('view',App\Category::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('category_index') }}">Category</a>
        </li>
        @endcan
        @can('view',App\Product::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('product_index') }}">Product</a>
        </li>
        @endcan
        @can('view', App\News::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('news_all') }}">News</a>
        </li>
        @endcan
        @can('view', App\News::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('link_list') }}">News Links</a>
        </li>
        @endcan
        @can('view', App\Role::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('role_index') }}">Role</a>
        </li>
        @endcan
        @can('view', App\User::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user_index') }}">User</a>
        </li>
        @endcan
        @can('view', App\Tag::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tag_index') }}">Taxonomy</a>
        </li>
        @endcan
        @can('view', App\Comment::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('comment_index') }}">Comment</a>
        </li>
        @endcan
        @if(Gate::allows('view-comments'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('comment_list', ['user_id' => Auth::id()]) }}">My Comments</a>
        </li>
        @endif
        @can('view', App\Privacy::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('view_policy') }}">Privacy Policy</a>
        </li>
        @endcan
        @can('view', App\Service::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('view_service') }}">Terms of Service</a>
        </li>
        @endcan
        @can('view', App\Contact::class)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('contact_index') }}">Messages</a>
        </li>
        @endcan
    </ul>
</div>