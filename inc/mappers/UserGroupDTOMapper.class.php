<?php

namespace Albatross;

include_once dirname(__DIR__) . '/models/UserGroupDTO.class.php';

use Albatross\models\UserGroupDTO;

require_once dirname(__DIR__, 4) . '/user/class/usergroup.class.php';

class UserGroupDTOMapper
{
	/**
	 * @param \UserGroup $userGroup
	 */
	public function toUserGroupDTO($userGroup): UserGroupDTO
	{
		$userGroupDTO = new UserGroupDTO();
		$userGroupDTO
			->setId($userGroup->id)
			->setLabel($userGroup->name);

		return $userGroupDTO;
	}

	/**
	 * @param \Albatross\models\UserGroupDTO $userGroupDTO
	 */
	public function toUserGroup($userGroupDTO): \UserGroup
	{
		global $db;
		$newUserGroup = new \UserGroup($db);

		$newUserGroup->id = $userGroupDTO->getId();
		$newUserGroup->name = $userGroupDTO->getLabel();

		return $newUserGroup;
	}
}
