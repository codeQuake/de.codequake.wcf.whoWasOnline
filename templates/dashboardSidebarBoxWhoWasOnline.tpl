{if WHO_WAS_ONLINE_SIDEBAR_DISPLAY == 'avatars'}
    <ul class="framedIconList">
        {foreach from=$whoWasOnline item=user}
            <li><a href="{link controller='User' object=$user}{/link}"
                   title="{$user->username} ({@$user->lastActivityTime|plainTime})"
                   class="framed jsTooltip">{@$user->getAvatar()->getImageTag(48)}</a></li>
        {/foreach}
    </ul>
{else if WHO_WAS_ONLINE_SIDEBAR_DISPLAY == 'usernames'}
    <ul class="dataList">
        {foreach from=$whoWasOnline item=user}
            <li><a href="{link controller='User' object=$user}{/link}" class="userLink"
                   data-user-id="{@$user->userID}">{@$user->getFormattedUsername()}</a></li>
        {/foreach}
    </ul>
{/if}
{if $__whoWasOnlineShowAllAvailable == true}
    <a class="button small more userWasOnlineAll jsOnly">{lang}wcf.user.whoWasOnline.showAll{/lang}</a>
{/if}
