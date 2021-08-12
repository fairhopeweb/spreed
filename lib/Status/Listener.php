<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2019 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Talk\Status;

use OCA\Talk\Events\ModifyParticipantEvent;
use OCA\Talk\Room;
use OCA\Talk\Signaling\Messages;
use OCA\UserStatus\Service\PredefinedStatusService;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IUserSession;
use OCP\UserStatus\IManager;
use OCP\UserStatus\IUserStatus;

class Listener {
	/** @var IUserSession */
	public $userSession;

	public function __construct(IUserSession $userSession) {
		$this->userSession = $userSession;
	}

	public static function register(IEventDispatcher $dispatcher): void {
		$dispatcher->addListener(Room::EVENT_BEFORE_SESSION_JOIN_CALL, static function (ModifyParticipantEvent $event) {
			$room = $event->getRoom();
			/** @var \OCA\Talk\Chat\SystemMessage\Listener $listener */
			$listener = \OC::$server->get(self::class);
			/** @var IManager $statusManager */
			$statusManager = \OC::$server->get(IManager::class);

			$statusManager->setUserStatus($listener->userSession->getUser()->getUID(), PredefinedStatusService::CALL, IUserStatus::DND, true);
			$messages = \OC::$server->get(Messages::class);
			$messages->addMessageForAllParticipants($event->getRoom(), 'refresh-participant-list');
		});

		$dispatcher->addListener(Room::EVENT_AFTER_SESSION_LEAVE_CALL, static function (ModifyParticipantEvent $event) {
			$room = $event->getRoom();
			/** @var \OCA\Talk\Chat\SystemMessage\Listener $listener */
			$listener = \OC::$server->get(self::class);
			/** @var IManager $statusManager */
			$statusManager = \OC::$server->get(IManager::class);

			$statusManager->revertUserStatus($listener->userSession->getUser()->getUID(), PredefinedStatusService::CALL, IUserStatus::DND);
			$messages = \OC::$server->get(Messages::class);
			$messages->addMessageForAllParticipants($event->getRoom(), 'refresh-participant-list');
		});
	}
}
