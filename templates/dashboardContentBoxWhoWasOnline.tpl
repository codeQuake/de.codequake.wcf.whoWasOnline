<div class="container containerPadding marginTop">
    <fieldset>
        <legend>{lang}wcf.user.whoWasOnline.title{/lang} <span class="badge">{#$whoWasOnlineListCount}</span></legend>
        {if WHO_WAS_ONLINE_ENABLE_RECORD && WHO_WAS_ONLINE_RECORD > 0}
            <small>{lang}wcf.user.whoWasOnline.record{/lang}</small>
        {/if}

        {if WHO_WAS_ONLINE_CONTENT_DISPLAY == 'avatars'}
            <ul class="framedIconList">
                {foreach from=$whoWasOnline item=user}
                    <li><a href="{link controller='User' object=$user}{/link}"
                           title="{lang}wcf.user.whoWasOnline.avatar.title{/lang}"
                           class="framed jsTooltip">{@$user->getAvatar()->getImageTag(32)}</a></li>
                {/foreach}
            </ul>
        {else if WHO_WAS_ONLINE_CONTENT_DISPLAY == 'usernames'}
            <ul class="dataList">
                {foreach from=$whoWasOnline item=user}
                    <li><a href="{link controller='User' object=$user}{/link}" class="userLink"
                           data-user-id="{@$user->userID}">{@$user->getFormattedUsername()}</a></li>
                {/foreach}
            </ul>
        {/if}
    </fieldset>
</div>
