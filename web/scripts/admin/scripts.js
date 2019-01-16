window.addEventListener('load', function () {

    var intervals = [],
        addProcessingInterval = function (el) {
            if (typeof intervals[el.data('id')] == 'undefined') {
                intervals[el.data('id')] = setInterval(function () {
                        $.get(el.data('href'), function (json) {
                            if (typeof json == 'object') {
                                if (typeof json.completed != 'undefined') {
                                    if (json.completed) {
                                        clearInterval(intervals[el.data('id')]);
                                        delete intervals[el.data('id')];
                                        el.parents('tr:eq(0)').find('td:eq(2)').html(json.offers);
                                        el.parents('td:eq(0)').html('<span class="text-success">'+json.text+'</span>');
                                    } else if (typeof json.error != 'undefined' && json.error) {
                                        alert(json.msg);
                                        clearInterval(intervals[el.data('id')]);
                                        delete intervals[el.data('id')];
                                        el.parents('td:eq(0)').html('<span class="text-danger">'+json.text+'</span>');
                                    }
                                }
                            }
                        });
                    },
                    5000
                );
            }
        }
        ;

    if ($('.importProcessing[data-status=new]').length) {
        $('.importProcessing[data-status=new]').each(function (i) {
            addProcessingInterval($(this));
        })
    }
})
;
