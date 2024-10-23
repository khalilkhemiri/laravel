@extends('layouts.app')

@section('content')
<script>
    window.Laravel = {
        csrfToken: '{{ csrf_token() }}',
        user: @json(auth()->user()), // This will pass the authenticated user information to your JavaScript
    };
</script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @vite(['resources/js/bootstrap.js'])
    <style>

.status-indicator {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-left: 5px;
}

.status-indicator.online {
    background-color: #28a745; 
}

.status-indicator.offline {
    background-color: #dc3545; 
}

        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
        }

        .rate:not(:checked)>input {
            position: absolute;
            display: none;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ccc;
        }

        .rated:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ccc;
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #ffc700;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #c59b08;
        }

        .star-rating-complete {
            color: #c59b08;
        }

        .rating-container .form-control:hover,
        .rating-container .form-control:focus {
            background: #fff;
            border: 1px solid #ced4da;
        }

        .rating-container textarea:focus,
        .rating-container input:focus {
            color: #000;
        }

        .rated {
            float: left;
            height: 46px;
            padding: 0 10px;
        }

        .rated:not(:checked)>input {
            position: absolute;
            display: none;
        }

        .rated:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ffc700;
        }

        .rated:not(:checked)>label:before {
            content: '★ ';
        }

        .rated>input:checked~label {
            color: #ffc700;
        }

        .rated:not(:checked)>label:hover,
        .rated:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rated>input:checked+label:hover,
        .rated>input:checked+label:hover~label,
        .rated>input:checked~label:hover,
        .rated>input:checked~label:hover~label,
        .rated>label:hover~input:checked~label {
            color: #c59b08;
        }
    </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">

    <section id="contact" class="contact section">
        <div class="container aos-init aos-animate" data-aos="fade">
            <div class="row gy-5 gx-lg-5">
                <div class="col-lg-4">

                    <br><br>
                    <div class="info">
                        <!-- Display plant image -->
                        <img src="{{ asset('/img/plant.png') }}" alt="Image" class="img-fluid">
                        <!-- Display plant details -->
                        <h2>{{ $plant->common_name }}</h2>
                        <h2>{{ $plant->scientific_name }}</h2>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Create form for user to add advice -->
                    <section id="blog-comments" class="blog-comments section mt-5">
                        <div class="container">
                            <h4 class="comments-count">{{ $advices->count() }} Advices</h4>

                            @foreach ($advices as $advice)
                                <div id="comment-{{ $advice->id }}" class="comment">
                                    <div class="d-flex position-relative">
                                        @if ($advice->user_id)
                                            <div class="comment-img">
                                                <img src="{{ asset('/img/avatars/1.png') }}" alt="">
                                            </div>
                                        @else
                                            <div class="comment-img">
                                                <img src="{{ asset('/img/avatars/anonymous.webp') }}" alt="">
                                            </div>
                                        @endif

                                        <div>
                                            <h5>
                                            @if ($advice->user_id)
    <a href="" data-user-id="{{ $advice->user->id }}">
        {{ $advice->user->name }}
        @if ($advice->user->last_activity && \Carbon\Carbon::parse($advice->user->last_activity)->gt(\Carbon\Carbon::now()->subMinutes(2)))
            <span class="status-indicator online"></span>
        @else
            <span class="status-indicator offline"></span>
        @endif
    </a>


                    @else
                        <span>Anonymous</span>
                    @endif
                                                @if (Auth::check())
                                                    <!-- Check if the user is authenticated -->
                                                    <button type="button" class="btn btn-link"
                                                        @if (Auth::id() === $advice->user_id) disabled @endif
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#ratingModal{{ $advice->id }}">
                                                        Rate
                                                    </button>
                                                @endif

                                            </h5>
                                            <time
                                                datetime="{{ $advice->created_at }}">{{ $advice->created_at->format('d M, Y') }}</time>
                                            <p>{{ $advice->content }}</p>

                                            <div class="average-rating position-absolute top-0 end-0 me-2 mt-2">
                                                <span class="text-warning">
                                                    @if ($advice->average_rating)
                                                        @for ($i = 0; $i < floor($advice->average_rating); $i++)
                                                            ★
                                                        @endfor
                                                        @if ($advice->average_rating - floor($advice->average_rating) >= 0.5)
                                                            ★
                                                        @endif
                                                        @for ($i = 0; $i < 5 - ceil($advice->average_rating); $i++)
                                                            ☆
                                                        @endfor
                                                    @else
                                                        No ratings yet
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="comments-section-{{ $advice->id }}">
                                        @if ($advice->ratings->whereNotNull('comment')->isNotEmpty())
                                            <!-- Check if there are ratings with non-null comments -->
                                            @foreach ($advice->ratings as $rating)
                                                @if (!is_null($rating->comment))
                                                    <!-- Only display the comment if it's not null -->
                                                    <div id="comment-reply-{{ $rating->id }}"
                                                        class="comment comment-reply">
                                                        <div class="d-flex">
                                                            <div class="comment-img">
                                                                <img src="{{ asset('/img/avatars/6.png') }}"
                                                                    alt="">
                                                            </div>
                                                            <div>
                                                                <h5>
                                                                    <a
                                                                        href="">{{ optional($rating->user)->name ?? 'Anonymous' }}</a>
                                                                    <a href="#" class="reply"><i
                                                                            class="bi bi-reply-fill"></i> Reply</a>
                                                                </h5>
                                                                <time
                                                                    datetime="{{ $rating->created_at }}">{{ $rating->created_at->format('d M, Y') }}</time>
                                                                <p>{{ $rating->comment }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>


                                </div>



                                <div class="modal fade" id="ratingModal{{ $advice->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="ratingModalLabel{{ $advice->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="ratingModalLabel{{ $advice->id }}">Rate
                                                    Advice</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>To help us get better, please rate this advice.</p>
                                                <form action="{{ route('ratings.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="advice_id" value="{{ $advice->id }}">

                                                    <!-- Star Rating -->
                                                    <div class="rate">
                                                        @php
                                                            // Get the user's existing rating if available
$existingRating = $advice
    ->ratings()
    ->where('user_id', Auth::id())
                                                                ->first();
                                                        @endphp

                                                        <input type="radio" id="star5-{{ $advice->id }}" name="score"
                                                            value="5" required
                                                            @if ($existingRating && $existingRating->score == 5) checked @endif />
                                                        <label for="star5-{{ $advice->id }}" title="5 stars">5
                                                            stars</label>
                                                        <input type="radio" id="star4-{{ $advice->id }}" name="score"
                                                            value="4"
                                                            @if ($existingRating && $existingRating->score == 4) checked @endif />
                                                        <label for="star4-{{ $advice->id }}" title="4 stars">4
                                                            stars</label>
                                                        <input type="radio" id="star3-{{ $advice->id }}" name="score"
                                                            value="3"
                                                            @if ($existingRating && $existingRating->score == 3) checked @endif />
                                                        <label for="star3-{{ $advice->id }}" title="3 stars">3
                                                            stars</label>
                                                        <input type="radio" id="star2-{{ $advice->id }}" name="score"
                                                            value="2"
                                                            @if ($existingRating && $existingRating->score == 2) checked @endif />
                                                        <label for="star2-{{ $advice->id }}" title="2 stars">2
                                                            stars</label>
                                                        <input type="radio" id="star1-{{ $advice->id }}" name="score"
                                                            value="1"
                                                            @if ($existingRating && $existingRating->score == 1) checked @endif />
                                                        <label for="star1-{{ $advice->id }}" title="1 star">1
                                                            star</label>
                                                    </div>

                                                    <div class="form-group">
                                                        <textarea name="comment" class="form-control" placeholder="Your comment (optional)">{{ $existingRating->comment ?? '' }}</textarea>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    @if (Auth::check())
                        <section id="comment-form" class="comment-form section">
                            <div class="container">
                                <form action="{{ route('advice.store') }}" method="POST">
                                    @csrf
                                    <h4>Help with your advice</h4>
                                    <input type="hidden" name="plant_id" value="{{ $plant->id }}">
                                    <div class="row">
                                        <div class="col form-group">
                                            <textarea name="content" class="form-control" placeholder="Your advice" required></textarea>
                                        </div>
                                    </div>

                                    <div>
                                        <input type="checkbox" name="anonymous" id="anonymous" value="1">
                                        <label for="anonymous">Submit anonymously</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Post it</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    @endif


                </div>
            </div>
        </div>
    </section>
@endsection
