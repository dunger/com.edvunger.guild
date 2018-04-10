/**
 * Namespace for raid management.
 */
WCF.ACP.Guild = { };

/**
 * Namespace for game management.
 */
WCF.ACP.Guild.Games = { };

/**
 * Generic implementation to enable users.
 */
WCF.ACP.Guild.Games.GameHandler = {
    /**
     * action proxy
     * @var	WCF.Action.Proxy
     */
    _proxy: null,

    /**
     * Initializes WCF.ACP.User.EnableHandler on first use.
     */
    init: function() {
        this._proxy = new WCF.Action.Proxy({
            success: $.proxy(this._success, this)
        });

        $('#guildSelectApi').change($.proxy(function(event) {
            var $button = $(event.currentTarget);

            if ($button.val() != 0) {
                this.getFields($button.val());
            } else {
                $('#fieldData').html('');
			}
        }, this));
    },

    /**
     * Enables users.
     *
     * @param	<integer>	memberID
     */
    getFields: function(gameID) {
        this._proxy.setOption('data', {
            actionName: 'getFields',
            className: 'guild\\data\\game\\GameAction',
            parameters: {
                gameID: gameID
            }
        });
        this._proxy.sendRequest();
    },

    /**
     * Handles successful AJAX calls.
     *
     * @param	object		data
     * @param	string		textStatus
     * @param	jQuery		jqXHR
     */
    _success: function(data, textStatus, jqXHR) {
        $('#fieldData').html(data.returnValues);
    }
};

/**
 * Namespace for raid member management.
 */
WCF.ACP.Guild.Member = { };

/**
 * Generic implementation to manage member.
 */
WCF.ACP.Guild.Member.EnableHandler = {
	_guildID: 0,

	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * Initializes WCF.ACP.User.EnableHandler on first use.
	 */
	init: function($guildID) {
		this._guildID = $guildID

		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		$('.jsGuildButton').click($.proxy(function(event) {
			var $button = $(event.currentTarget);
			if ($button.data('enabled') && typeof $button.data('enabled') !== 'undefined') {
				this.disable($button.data('memberID'), this._guildID);
			}
			else if (!$button.data('enabled') && typeof $button.data('enabled') !== 'undefined') {
				this.enable($button.data('memberID'), this._guildID);
			}
			else if ($button.data('setuser')) {
				this.setUser(
					$button.data('memberID'),
                    this._guildID,
					$('input[data-member-id="' + $button.data('memberID') + '"]').val(),
					$('select[data-group-member-id="' + $button.data('memberID') + '"]').val(),
					$('select[data-rank-member-id="' + $button.data('memberID') + '"]').val(),
					$('select[data-role-member-id="' + $button.data('memberID') + '"]').val(),
                    $('select[data-ismain-member-id="' + $button.data('memberID') + '"]').val()
				);
			}
			else if ($button.data('deleteuser')) {
				this.deleteUser($button.data('memberID'), this._guildID);
			}
		}, this));
	},
	
	/**
	 * Disables member.
	 * 
	 * @param	<integer>	memberIDs
	 */
	disable: function(memberID, guildID) {
		this._proxy.setOption('data', {
			actionName: 'disable',
			className: 'guild\\data\\member\\MemberAction',
			parameters: {
				memberID: memberID,
                guildID: guildID
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Enables member.
	 * 
	 * @param	<integer>	memberID
	 */
	enable: function(memberID, guildID) {
		this._proxy.setOption('data', {
			actionName: 'enable',
			className: 'guild\\data\\member\\MemberAction',
			parameters: {
				memberID: memberID,
                guildID: guildID
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Enables users.
	 *
     * @param	<integer>	memberID
     * @param	<string>	username
     * @param	<integer>	groupID
     * @param	<integer>	rankID
     * @param	<integer>	roleID
     * @param	<bool>		isMain
	 */
	setUser: function(memberID, guildID, username, groupID, rankID, roleID, isMain) {
		this._proxy.setOption('data', {
			actionName: 'setUser',
			className: 'guild\\data\\member\\MemberAction',
			parameters: {
				memberID: memberID,
                guildID: guildID,
				username: username,
				groupID: groupID,
				rankID: rankID,
                roleID: roleID,
                isMain: isMain
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Enables users.
	 * 
	 * @param	<integer>	memberID
	 */
	deleteUser: function(memberID, guildID) {
		this._proxy.setOption('data', {
			actionName: 'deleteUser',
			className: 'guild\\data\\member\\MemberAction',
			parameters: {
				memberID: memberID,
                guildID: guildID
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles successful AJAX calls.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		for (var i = 0, len = data.objectIDs.length; i < len; i++) {
			var $member = $('tr[data-member-id="' + data.objectIDs[i] + '"]');
			if (data.actionName == 'disable') {
				$member.find('.jsEnableButton').attr('data-enabled', false).data('enabled', false).attr('data-tooltip', $member.find('.jsEnableButton').data('enableMessage')).removeClass('fa-check-square-o').addClass('fa-square-o');
			}
			else if (data.actionName == 'enable') {
				$member.find('.jsEnableButton').attr('data-enabled', true).data('enabled', true).attr('data-tooltip', $member.find('.jsEnableButton').data('disableMessage')).removeClass('fa-square-o').addClass('fa-check-square-o');
			} else if (data.actionName == 'setUser') {
				$member.find('.columnUserName > .columnUserNameText > .columnUserNameTextData').html(data.returnValues);
				$member.find('.columnUserName > .columnUserNameText').removeClass('invisible');
				$member.find('.columnUserNameForm').addClass('invisible');
			} else if (data.actionName == 'deleteUser') {
				$member.find('.columnUserName > .columnUserNameText > .columnUserNameTextData').html('');
				$member.find('.columnUserName > .columnUserNameText').addClass('invisible');
				$member.find('.columnUserNameForm').removeClass('invisible');
			}
		}

		var $notification = new WCF.System.Notification();
		$notification.show(function() { });
		//$notification.show(function() { window.location.reload(); });
	}
};