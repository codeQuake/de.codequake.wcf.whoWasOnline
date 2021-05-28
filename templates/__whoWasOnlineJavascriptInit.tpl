{if $__whoWasOnlineShowAllAvailable|isset && $__whoWasOnlineShowAllAvailable === true}
    var $whoWasOnlineList = null;
    $('.userWasOnlineAll').click(function() {
        if ($whoWasOnlineList === null) {
            $whoWasOnlineList = new WCF.User.List('wcf\\data\\user\\online\\WhoWasOnlineAction', '{lang}wcf.user.whoWasOnline.title{/lang}');
        }
        $whoWasOnlineList.open();
    });
{/if}
