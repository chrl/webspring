        function catcher(id)
        {
            window.inputs = new Array();
            
            window.busy = true;
            
            //$('#'+id+'_submit').fadeOut('fast');
            
            $('#' + id+' input').each(function(elem){
                window.inputs.push({id: $(this).attr('id'), value: $(this).val()});
            });

            $('#' + id+' textarea').each(function(elem){
                window.inputs.push({id: $(this).attr('id'), value: $(this).val()});
            });
            $('#' + id+' select').each(function(elem){
                window.inputs.push({id: $(this).attr('id'), value: $(this).val()});
            });            
            
            $.post('/catcher/',{method: 'catch', entity: id, data: $.toJSON(window.inputs)}, function(data){
                result = $.parseJSON(data);
                if (result['result']=='check')
                {
                    $('#'+result['mark']).css('border','1px solid red');
                    $('#'+result['mark']).change(function(){
                        $('#msg').html(result['msg']).fadeOut('fast');
                        $('#'+result['mark']).css('border','1px solid gray');
                    });
                    $('#msg').html(result['msg']).fadeIn('fast');
                    $('#'+id+'_submit').fadeIn('fast');
                }
                
                if (result['result']=='ok')
                {
                    if (result['uri'])
                    {
                        top.location = result['uri'];
                    } else
                    
                    $('#'+id).fadeOut('slow',function(){
                        $('#'+id).html(result['msg']);
                        $('#'+id).fadeIn('fast');
                    });
                }
            });
        }
