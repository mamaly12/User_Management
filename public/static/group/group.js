groups = document.getElementById('groups');
if(groups)
{
    groups.addEventListener('click', event => {
    if(event.target.className === 'btn btn-danger delete-group')
    {
        if(confirm('Are you sure?'))
        {
            const id = event.target.getAttribute('data-id');
            $.ajax({
                url: '/group/delete',
                type: "POST",
                data: { "id":id},
                dataType: "json",
                success : function(data) {
                    if ( data.result==true ) {
                        location.href = '/group/list';
                    }else{
                        alert(data.message);
                    }
                }
            });
        }
    }
    });
}

groupUsers = document.getElementById('groupUsers');
if(groupUsers)
{
    groupUsers.addEventListener('click', event => {
        if(event.target.className === 'btn btn-danger delete-groupUsers')
        {
            if(confirm('Are you sure?'))
            {
                const userId = event.target.getAttribute('data-userId');
                const groupId = event.target.getAttribute('data-groupId');
                $.ajax({
                    url: '/group/user/delete',
                    type: "POST",
                    data: { "userId": userId, "groupId":groupId},
                    dataType: "json",
                    success : function(data) {
                        if ( data.result==true ) {
                            location.href = '/group/'+groupId+'/users';
                        }else{
                            alert(data.message);
                        }
                    }
                });
            }
        }
        else if(event.target.className === 'btn btn-dark add-groupUsers')
        {
            if(confirm('Are you sure?'))
            {
                const userId = event.target.getAttribute('data-userId');
                const groupId = event.target.getAttribute('data-groupId');
                $.ajax({
                    url: '/group/user/add',
                    type: "POST",
                    data: { "userId": userId, "groupId":groupId},
                    dataType: "json",
                    success : function(data) {
                        if ( data ) {
                            location.href ="/group/"+groupId+"/user/add";
                        }
                    }
                });
            }
        }
    });
}