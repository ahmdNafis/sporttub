@extends('master')
@section('page')
    <?php 
        $roles = null;
        if(Auth::check()) $roles = Auth::user()->roles()->pluck('role_name')->toArray();

    ?>
    
        @if(Auth::check() && count($roles) < 2 && !in_array('Commentator', $roles))
            @include('partials.sidebar')
        @endif
    <div class="row newsTop {{  Auth::check() ? 'leftShift' : ''  }}">
        
        <div class="col-md-9">
            @include('partials.message')
            <h2 class="text-center"><a href="{{ isset($metadata['link']) ? $metadata['link'] : '#' }}">{{ $metadata['title'] }}</a></h2>
            <div class="newsDetail"><span>{{ $category }}</span>{{ 'Published on '.date('d/m/y', strtotime($metadata['published_date'])) }}</div>
            <hr />
            <?php 
                $content = explode('.', $news['content']);
                array_splice($content, count($content)-3);
                $refined = '';
                for($i=$j=0; $i<count($content); $i++) {
                    if(($i+1)%3!=0) $refined .= $content[$i]; 
                    else $refined .= ".<br /><br />".$content[$i];
                }
            ?>
            <p class="text-justify newsContent"><?php echo $refined; ?></p>
            
            @if($tags)
            <p>
                <ul class="nav nav-pills">
                    @foreach($tags as $id => $tag)
                        <li class="nav-item">
                            <h4><span class="badge badge-secondary">{{ $tag }}</span></h4>
                        </li>
                    @endforeach
                </ul>
            </p>
            @endif
            <hr />
            <h3>Comments</h3>
            @if(!Auth::check())
                <form id="commentLogin" action="{{route('comment_login')}}" method="POST" class="row">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group col-md-8">
                            <label for="emailInput">Email *</label>
                            <input type="email" name="email" required autocomplete="email" autofocus class="form-control" id="emailInput">
                        </div>
                        <div class="form-group col-md-8">
                            <label for="passwordInput">Password *</label>
                            <input type="password" name="password" required class="form-control" autocomplete="current-password" id="passwordInput">
                        </div>                                   
                        <div class="col-md-8">
                            <button id="commentLoginSubmit" type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </form>
            @elseif(Auth::check() && in_array('Commentator', $roles))
            <form id="commentLogin" action="{{route('comment_store')}}" method="POST" class="row">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}" />
                    <input type="hidden" name="object_id" value="{{ $object_id }}" />
                    <div class="col-md-12">
                    <div class="form-group col-md-8">
                        <label for="contentInput">Comment</label>
                        <textarea name="content" rows="6" maxlength="250" class="form-control" id="contentInput"></textarea>
                    </div>                   
                    <div class="col-md-8">
                        <button id="commentSubmit" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </div>
                </form>
            @endif
            @if(count($comments)!=0)
            
                @foreach($comments as $name => $content)
                <blockquote class="blockquote">
                    <hr />
                    <h5><cite title="{{ $name }}">{{ $name }}</cite></h5>
                    <hr />
                    <h6 class="mb-0">{{ $content }}</h6>
                </blockquote>
                @endforeach
            
            @endif
        </div>
        @if(!Auth::check() && count($products)!=0)
        <div class="col-md-3 productsShow">
            
            <ul class="list-group">
                @foreach($products as $k => $arr)
                <li class="list-group-item">
                    <img class="img-thumbnail" src="{{ asset('storage/uploadedFile/'.$products[$k]['image_link']) }}"/>
                    
                    <p style="clear: both;">
                        <p style="float: left;"><a href="{{ $products[$k]['product_link']}}">{{ $products[$k]['product_name'] }}</a></p>
                        <p style="float: right;">{{ '$'.$products[$k]['retail_price'] }}</p>
                    </p>
                </li>
                @endforeach    
            </ul>
            
        </div>
        @endif

        
    </div>
@endsection