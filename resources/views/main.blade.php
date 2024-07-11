<!doctype html>
<html lang="en">
    <head>
        <title>Text-to-image</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />


        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>

    <body style="background-color: #fb503b;">
        <header>
            <!-- place navbar here -->
        </header>
        <main>

            <div class="container mt-5">

            <div class="card bg-white">
                <div class="card-body">
                    <img class="card-top-image m-3" style="width:5%;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/120px-Laravel.svg.png"  />
                    <h4 class="card-title mb-4">Laravel Text to image</h4>
                    <form method="post" id="promptForm">
                        @csrf
                        <input type="text" name="prompt" class="form-control mb-2" placeholder="Enter the text">
                        <button type="submit" class="btn btn-primary mb-2 btnSubmit">SUBMIT</button>
                    </form>
                    <p class="card-text">Output : </p>
                    <div class="border border-3 border-secondary row MainImageContainer">
                            <img class="card-img-down p-3" id="imageContent" style="height: 512px; width: 512px;" src="https://pub-3626123a908346a7a8be8d9295f44e26.r2.dev/generations/a6385c1e-4322-4634-93ad-67b6a473396c-0.png" alt="Image would display here." />
                        {{-- <img class="card-img-down" src="{{ Session::get('image') ? Session::get('image') : '' }}" alt="Image would display here." /> --}}
                    </div>
                </div>
            </div>

            <div class="row">

                {{-- {{ dd($prompts[3]->images) }} --}}
                @foreach ($prompts as $prompt)

                <div class="card mt-3">
                    <div class="card-body">
                        <h4 class="card-title">Prompt : {{ $prompt->prompt }}</h4>
                        <div>
                            <button type="button" class="btn btn-primary">Try this</button>
                            <button type="button" class="btn btn-danger">Delete</button>
                        </div>
                        <div class="row">
                            {{-- {{ dd($prompt->prompt) }} --}}
                            @foreach ($prompt->images as $image)

                                    <img class="img img-fluid m-3" id="imageContent" style="height: 256px; width: 256px;" src="{{ asset('storage/uploads/images/'.$image->source) }}" alt="Image would display here." />

                            @endforeach
                        </div>
                    </div>
                </div>

                @endforeach


            </div>

            </div>

        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>

        <script>

            $(document).ready(function () {

                $(document).on('submit','#promptForm', function (e) {

                    e.preventDefault();

                    let fd = new FormData(this);

                    let spinner = `

                        <div class="spinner-border spinnerClose" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="spinner-grow spinnerClose" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                        </div>

                    `;
                    let image;

                    $.ajax({
                        type: "POST",
                        url: "{{ route('textToImage') }}",
                        data: fd,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (res) {

                            $('.btnSubmit').prop('disabled', true);
                            setTimeout(function() {
                                $('.btnSubmit').prop('disabled', false);
                            }, 5000);

                            $('.MainImageContainer').empty();
                            $('.MainImageContainer').append(spinner);
                            // $('.btnSubmit').prop('disabled', true);

                            // console.log(res);
                            for (let i = 0; i < res.length; i++) {

                                image = `<img class="img img-fluid m-3" id="imageContent" style="height: 512px; width: 512px;" src="`+res[i]+`" alt="Image would display here." />`;
                                console.log(res[i]);
                                $('.MainImageContainer').append(image);

                            }

                            // // $('.MainImageContainer').prepend(spinner);
                            // $('.MainImageContainer').prepend(spinner);

                            // $('#imageContent').attr('src', res);
                            // $('.btnSubmit').prop('disabled', false);

                            $('.spinnerClose').remove();
                        },
                        error: function (err) {
                            alert('error');
                        }
                    });

                });

            });

        </script>

    </body>
</html>
