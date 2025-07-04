export function KatiniModule(customOptions){
    console.log('KatiniModule init')

    /** 
     * Options
     */
    const defaultOptions = {
        confirmMessage: 'Weet u het zeker?',
    }

    const options = {...defaultOptions, ...customOptions}

    /**
     * Duplicate sidebar html to offcanvas sidebar
     */
    const staticSidebar = $('#staticSidebar')
    const sidebarContent = $('#sidebarContent')
    sidebarContent.html(staticSidebar.html())

    /**
    * Confirm action by adding the .confirm-action class
    */
    $('body').on('click', '.confirm-action', function (e) {
        let confirmMessage = $(this).attr('data-confirm-msg') ?? options.confirmMessage

        if ($(this).attr('data-confirm-detail')) confirmMessage = confirmMessage + "\n\n" + $(this).attr('data-confirm-detail')

        let confirmation = window.confirm(confirmMessage)

        if (confirmation) {
            return true;
        }

        e.preventDefault()
        return false;
    })

    /**
     * Refresh the current window (ignore changes)
     */
    $('body').on('click', '.btn-page-refresh', function (e) {
        window.location.reload()
    })

    /**
     * Update time
     */
    setInterval(function () {
        // Load current times
        $.ajax({
            url: options.baseUrl + 'ajax/huidige-tijd',
            success: function (data) {
                $('.katini-time').each(function (index) {
                    let timeZone = $(this).attr('data-katini-time-zone')
                    let timeFormat = $(this).attr('data-katini-time-format')

                    $(this).text(data[timeZone][timeFormat])
                })
            }
        })
    }, 30000)

    /**
     * just-validate config
     */
    /**window.justValidateConfig = {
        errorFieldCssClass: 'is-invalid',
    }**/
}


