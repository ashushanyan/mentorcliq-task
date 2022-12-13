<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>
<section class="container">
    <div class="p-5  bg-light row">
        <div class="col">
            <h2 class="bd-title"> Generate your csv file ...</h2>
            <form class="row row-cols-lg-auto g-3 align-items-center" enctype="multipart/form-data">
                <div class="col-12">
                    <label class="visually-hidden" for="inlineFormInputGroupUsername"></label>
                    <div class="input-group">
                        <div class="input-group-text"><i class="bi bi-filetype-csv"></i></div>
                        <input type="file" class="form-control" name="document" id="inlineFormInputGroupUsername"
                               placeholder="csv file"/>
                    </div>
                </div>

                <div class="col-12">
                    <button type="button" id="submit-btn-csv-form" class="btn btn-success">Submit</button>
                </div>
            </form>
            <div id="response-message-for-csv" class="invalid-feedback"></div>
            <div class="mt-4  bg-light" id="result-section">
                <h2> Result </h2>
                <p>Names - <span class="names"></span></p>
                <p>Score - <span class="average-score"></span></p>
            </div>
        </div>
        <div class="col">
            <h2 class="bd-title"> Set Percents</h2>
            <form class="row g-3" id="form-score">
               <div class="col-12">
                    <label for="inputAddress" class="form-label">Percent for same division</label>
                    <input type="number" name="division" class="form-control setScoreInputs" id="division" placeholder="1 - 100%" min="1" max="100">
                </div>
                <div class="col-12">
                    <label for="inputAddress2" class="form-label">Percent for age ( <= 5 years)</label>
                    <input type="number" name="age" class="form-control setScoreInputs" id="age" placeholder="1 - 100%" min="1" max="100">
                </div>
                <div class="col-12">
                    <label for="inputAddress2" class="form-label">Percent for same timezone</label>
                    <input type="number" name="timezone" class="form-control setScoreInputs" id="timezone" placeholder="1 - 100%" min="1" max="100">
                </div>
                <small>sum of these inputs should be 100%, now it's <b><span id="totalScore" >0</span></b> %</small>

                <div class="col-6">
                    <div id="response-message-for-score" class="col-6 invalid-feedback"></div>
                </div>

                <div class="col-6">
                    <button type="button" class="btn btn-success float-end" id="submit-btn-score-form" disabled>Submit</button>
                </div>
            </form>
        </div>

    </div>

</section>
<script src="app/public/js/main.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>