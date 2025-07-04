function fetchAndAppend(modalFile){
    return fetch(window.katiniSiteUrl + 'assets/Katini/modals/' + modalFile + '.html').then(function (response) {
        if (response.ok == false) {
            console.log('Could not load findSupporter modal');
        }
        return response.text()
    }).then(function (html) {
        $('body').append(html)
    })
}


export function findSupporterModal(options) {
    let modal = null;
    let onSelect = () => {}
    
    /**
     * Append modal html to body
     */
    fetchAndAppend('findSupporter')

    $(options.triggerBtn).on('click', function () {
        init()
        modal = new bootstrap.Modal('#findSupporterModal')
        modal.show()
    })

    /** 
     * Modal Logic
     */
    function init(){
        let jsonUrl = '/supporters/find.json?limit=7&q='
        let jsonRequest = null

        let resultMessage = $('#resultMessage')
        let resultsRow = $('#resultsRow')
        let resultsTemplate = Handlebars.compile($('#resultsTemplate').html())

        $('#findSupporterQuery').on('input', function () {
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

            jsonRequest = $.getJSON(jsonUrl + query, function (data) {
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
        
        /**
         * Select button
         */
        $('body').on('click', '.btn-select-supporter', function () {
            modal.hide()
            
            let supporter = {
                id: $(this).attr('data-supporter-id'),
                name: $(this).attr('data-supporter-display-name'),
            }

            onSelect(supporter)
        })
    } // /init

    /**
     * Return Object
     */
    return {
        onSelect: function (callback) {
            onSelect = callback
        }
    }
}