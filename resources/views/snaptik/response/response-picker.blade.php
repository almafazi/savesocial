<div class="container" data-id="Video">
    <div class="row download-box">
        <div class="col-12 col-md-6">
            <div class="down-left">
                <div class="user-avatar">
                    <img src="{{ collect($posts['metadata']['cover']['url_list'])->first() }}" alt="thumbnail" id="thumbnail">
                </div>

                <div class="user-info">
                    <div class="user-fullname">{{ $posts['metadata']['desc'] }}</div>
                    <div class="user-username">{{ $posts['metadata']['author'] }}</div>
                    <div class="user-username">
                        <i class="ph ph-bookmark"></i> <span class="me-2">
                            {{ shortNumber($posts['metadata']['statistics']['collect_count']) }}
                        </span>
                        <i class="ph ph-chat-circle-dots"></i> <span class="me-2">
                            {{ shortNumber($posts['metadata']['statistics']['comment_count']) }}
                        </span>
                        <i class="ph ph-download"></i> <span class="me-2">
                            {{ shortNumber($posts['metadata']['statistics']['download_count']) }}
                        </span>
                        <i class="ph ph-play"></i> <span class="me-2">
                            {{ shortNumber($posts['metadata']['statistics']['play_count']) }}
                        </span>
                        <i class="ph ph-share-network"></i> <span class="me-2">
                            {{ shortNumber($posts['metadata']['statistics']['share_count']) }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="/" class="btn btn-main btn-back btn-backpc">
                <svg width="20" height="21" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#a)"><path d="M14.708 6.286A6.631 6.631 0 0 0 10 4.328a6.658 6.658 0 0 0-6.658 6.666A6.658 6.658 0 0 0 10 17.661c3.108 0 5.7-2.125 6.442-5h-1.734A4.992 4.992 0 0 1 10 15.994c-2.758 0-5-2.241-5-5 0-2.758 2.242-5 5-5a4.93 4.93 0 0 1 3.517 1.484l-2.684 2.683h5.834V4.328l-1.959 1.958Z" fill="#fff"></path></g><defs><clipPath id="a"><path fill="#fff" transform="translate(0 .994)" d="M0 0h20v20H0z"></path></clipPath></defs></svg>Download another
            </a>
        </div>
    </div>

    <div class="tt-slide">
        @if($posts['status'] == 'picker')
            @foreach ($posts['picker'] as $item)
                <div>
                    <div>
                        <img src="{{ $item['url'] }}" alt="">
                        <div><a href="{{ route('frontend.index.tiktok-download-image') }}/{{ $item['download_url'] }}/{{ $posts['metadata']['author'] }}" class="btn btn-main">Download Photo</a></div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
