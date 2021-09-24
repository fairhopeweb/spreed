<?php

declare(strict_types=1);

/**
 * @copyright 2021 Christopher Ng <chrng8@gmail.com>
 *
 * @author Christopher Ng <chrng8@gmail.com>
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Talk\Profile;

use OCA\Talk\AppInfo\Application;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\IUserSession;
use OCP\L10N\IFactory;
use OCP\Profile\IAction;

class TalkAction implements IAction {

	/** @var string */
	private $userId;

	/** @var IFactory */
	private $l10nFactory;

	/** @var IUserManager */
	private $userManager;

	/** @var IUserSession */
	private $userSession;

	/** @var IUrlGenerator */
	private $urlGenerator;

	public function __construct(
		IFactory $l10nFactory,
		IUserManager $userManager,
		IUserSession $userSession,
		IURLGenerator $urlGenerator
	) {
		$this->l10nFactory = $l10nFactory;
		$this->userManager = $userManager;
		$this->userSession = $userSession;
		$this->urlGenerator = $urlGenerator;
	}

	public function preload(IUser $user): void {
		$this->userId = $user->getUID();
	}

	public function getAppId(): string {
		return Application::APP_ID;
	}

	public function getId(): string {
		return 'talk';
	}

	public function getTitle(): string {
		$visitingUser = $this->userSession->getUser();
		$targetUser = $this->userManager->get($this->userId);
		if ($visitingUser === $targetUser) {
			return $this->l10nFactory->get(Application::APP_ID)->t('Open Talk');
		}
		return $this->l10nFactory->get(Application::APP_ID)->t('Talk to %s', [$targetUser->getDisplayName()]);
	}

	public function getLabel(): string {
		$visitingUser = $this->userSession->getUser();
		$targetUser = $this->userManager->get($this->userId);
		if ($visitingUser === $targetUser) {
			return $this->l10nFactory->get(Application::APP_ID)->t('Open Talk');
		}
		return $this->l10nFactory->get(Application::APP_ID)->t('Talk');
	}

	public function getPriority(): int {
		return 10;
	}

	public function getIcon(): string {
		return $this->urlGenerator->getAbsoluteURL($this->urlGenerator->imagePath('spreed', 'app-dark.svg'));
	}

	public function getTarget(): string {
		$visitingUser = $this->userSession->getUser();
		$targetUser = $this->userManager->get($this->userId);
		if ($visitingUser === $targetUser) {
			return $this->urlGenerator->linkToRouteAbsolute('spreed.Page.index');
		}
		return $this->urlGenerator->linkToRouteAbsolute('spreed.Page.index') . '?callUser=' . $this->userId;
	}
}
