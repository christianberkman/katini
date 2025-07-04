<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'delete-success' => ['Success', 'De supporter is verwijderd: ', 'alert-success'],
]);

?>
<div class="row">
    <form id="findSupporterForm">
        <div class="col-lg-6 m-auto mb-3">
            <div class="input-group">
                <span class="input-group-text">Vind supporter</span>
                <input type="text" name="findSupporterQuery" id="findSupporterQuery" class="form-control"
                    placeholder="Begin met typen..." />
                <span class="input-group-text">
                    <?= bi('search'); ?>
                </span>
            </div>
        </div>
    </form>
</div><!--/row-->

<div class="row" id="messageRow">
    <p class="text-center" id="resultMessage"></p>
</div>

<div class="row row-cols-3" id="resultsRow">
</div><!--/row-->
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function() {
        let jsonUrl = '/supporters/find.json?q='
        let jsonRequest = null

        let resultMessage = $('#resultMessage')
        let resultsRow = $('#resultsRow')
        let resultsTemplate = Handlebars.compile($('#resultsTemplate').html())

        $('#findSupporterQuery').on('input', function() {
            resultMessage.html('Supporters vinden...')

            let query = $(this).val()

            if (query == '') {
                resultMessage.html('')
                resultsRow.html('')
                return true
            }


            // Abort request if running
            if (jsonRequest) {
                jsonRequest.abort()
            }

            jsonRequest = $.getJSON(jsonUrl + query, function(data) {
                console.log(data.resultCount)

                if (data.resultsCount == 0) {
                    resultMessage.html('Geen supporters gevonden.')
                    resultsRow.html('')
                } else {
                    if (data.resultsShown < data.resultsCount) {
                        resultMessage.html(data.resultsShown + ' van ' + data.resultsCount + ' gevonden supporters weergegeven')
                    } else {
                        resultMessage.html(data.resultsCount + ' supporters gevonden')
                    }
                    resultsRow.html(resultsTemplate(data))
                }
            })
        })
    })
</script>
<script id="resultsTemplate" type="text/x-handlebars-template">
    {{#each results}}
        <div class="col d-flex mb-3">
            <div class="card h-100 w-100">
                <div class="card-body pt-3">
                    <a href="{{url}}" class="stretched-link">
                        {{display_name}}
                    </a>
                    <br />
                    <em>{{address_city}}&nbsp;</em>
                </div><!--/card-body-->
            </div><!--/card-->
        </div><!--/col-->
    {{/each}}
</script>

<?php $this->endSection(); ?>