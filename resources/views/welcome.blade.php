<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Upload Huge File - Elegant Laravel</title>

    {{-- Resumable js --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.0.3/resumable.min.js"

        integrity="sha512-OmtdY/NUD+0FF4ebU+B5sszC7gAomj26TfyUUq6191kbbtBZx0RJNqcpGg5mouTvUh7NI0cbU9PStfRl8uE/rw=="

        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        {{-- Bootstrap js --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"

        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<body>

            <div class="video_upload mt-5" id="video_upload ">

                <div id="upload-container" class="text-center">

                    <button id="browseFile" class="btn btn-primary">Upload File</button>

                </div>

                <div style="display: none" class="progress mt-3 w-25 mx-auto" style="height: 25px">

                    <div class="progress-bar progress-bar-striped progress-bar-animated text-center" role="progressbar"

                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">

                        75%</div>

                </div>

            </div>

            <div class="d-none text-center" id="final_success">

                <h2 class="text-success">File Upload Successfull </h2>

            </div>

    {{-- Botostrap  --}}

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    {{-- Jquery  --}}

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>




    <script>

        let resumable = new Resumable({

            target: "{{ route('media.upload') }}",

            query: {

                _token: '{{ csrf_token() }}'

            },

            chunkSize: 10 * 1024 * 1024, // 10MB chunks

            testChunks: false,

            throttleProgressCallbacks: 1,

        });

        resumable.assignBrowse(document.getElementById('browseFile'));

        resumable.on('fileAdded', function(file) { // trigger when file picked

            showProgress();

            resumable.upload() // to actually start uploading.

        });

        resumable.on('fileProgress', function(file) { // trigger when file progress update

            updateProgress(Math.floor(file.progress() * 100));

        });

        resumable.on('fileSuccess', function(file, response) { // trigger when file upload complete
            console.log(response)
            $('#video_upload').addClass('d-none');

            $('#video_upload').hide();

            $('#final_success').removeClass('d-none');

            $('#final_success').show();

            hideProgress();

        });

        resumable.on('fileError', function(file, response) { // trigger when there is any error

            hideProgress();

            alert('file uploading error.')

        });

        let progress = $('.progress');

        function showProgress() {

            progress.find('.progress-bar').css('width', '0%');

            progress.find('.progress-bar').html('0%');

            progress.find('.progress-bar').removeClass('bg-success');

            progress.show();

        }

        function updateProgress(value) {

            progress.find('.progress-bar').css('width', `${value}%`)

            progress.find('.progress-bar').html(`${value}%`)

        }

        function hideProgress() {

            progress.hide();

        }

    </script>
    
</body>

</html>