$(function(){
    // table row
    $('tr[data-href] td').dblclick(function(e){
        window.location = $(this).parent().attr('data-href');
        e.stopPropagation();
    })

    // datepicker
    $( "input[name*=date]" ).datepicker({dateFormat:"dd/mm/yy", showOtherMonths: true, selectOtherMonths: true, dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ], firstDay: 1});
    $( "input[name*=birthday]" ).datepicker({dateFormat:"dd/mm/yy", changeMonth: true, changeYear: true, maxDate: "-10y", yearRange: "-40:-10", dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ], firstDay: 1});

    // countdown
    if(typeof target != 'undefined')
        $('#countdown').countdown({until: target});

    // checkall
    var check_btn = $('<div class="check-all"><i class="fa fa-check"></i> Tout cocher</div>');
    check_btn.click(function(){
        $(this).parent().find('input[type=checkbox]').attr('checked', true);
    });

    $('.staked-checkboxes').prepend(check_btn);

    // auto select
    $(".auto-select").focus(function() {
        var $this = $(this);
        $this.select();

        // Work around Chrome's little problem
        $this.mouseup(function() {
            $this.unbind("mouseup");
            return false;
        });
    });

    // extra fields
    var main_container = $('#hflan_lanbundle_tournament_extraFields');
    var add_btn = $('<button type="button" class="btn"><i class="fa fa-plus"></i> Ajouter un champ</button>');
    var index = main_container.children('.control-group').length;

    main_container.append(add_btn);
    add_btn.click(add_form);

    main_container.children('.control-group').each(function() {
        add_remove($(this));
    });

    function add_form(){
        var html = main_container.attr('data-prototype')
            .replace('<label class="control-label required">__name__label__</label>', '')
            .replace(/__name__/g, index);
        var $prototype = $(html);
        add_remove($prototype);

        add_btn.before($prototype);
        ++index;
    }

    function add_remove(elem){
        var remove_btn = $('<div class="remove-form"><i class="fa fa-times"></i></div>');

        remove_btn.click(function(){
            $(this).parent().animate({opacity:0}, 400, function(){
                $(this).remove();
            });
        })

        elem.prepend(remove_btn);
    }
});