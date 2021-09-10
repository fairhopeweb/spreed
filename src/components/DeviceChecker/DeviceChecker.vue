<!--
  - @copyright Copyright (c) 2021 Marco Ambrosini <marcoambrosini@pm.me>
  -
  - @author Marco Ambrosini <marcoambrosini@pm.me>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
-->

<template>
	<Modal v-if="modal" size="large" @close="closeModal">
		<div class="device-checker">
			<div class="device-checker__preview">
				<video v-show="videoPreviewAvailable"
					class="preview__video"
					disablePictureInPicture="true"
					tabindex="-1" />
				<div v-show="!videoPreviewAvailable"
					class="preview__novideo">
					<VideoBackground
						:display-name="displayName"
						:user="userId" />
					<Avatar v-if="userId"
						:size="128"
						:disable-menu="true"
						:disable-tooltip="true"
						:show-user-status="false"
						:user="userId"
						:display-name="displayName" />
					<div v-if="!userId"
						class="avatar guest">
						{{ firstLetterOfGuestName }}
					</div>
				</div>
			</div>
			<div class="device-checker__selectors">
				<MediaDevicesSelector kind="audioinput"
					:devices="devices"
					:device-id="audioInputId"
					:enabled="enabled"
					@update:deviceId="audioInputId = $event" />
				<MediaDevicesSelector kind="videoinput"
					:devices="devices"
					:device-id="videoInputId"
					:enabled="enabled"
					@update:deviceId="videoInputId = $event" />
			</div>
		</div>
	</Modal>
</template>

<script>
import Modal from '@nextcloud/vue/dist/Components/Modal'
import { devices } from '../../mixins/devices'
import MediaDevicesSelector from '../MediaDevicesSelector.vue'
import VideoBackground from '../CallView/shared/VideoBackground.vue'
import Avatar from '@nextcloud/vue/dist/Components/Avatar'

export default {
	name: 'DeviceChecker',

	components: {
		Modal,
		MediaDevicesSelector,
		VideoBackground,
		Avatar,
	},

	mixins: [devices],

	data() {
		return {
			modal: true,
		}
	},

	computed: {
		displayName() {
			return this.$store.getters.getDisplayName()
		},

		userId() {
			return this.$store.getters.getUserId()
		},
	},

	methods: {
		showModal() {
			this.modal = true
		},

		closeModal() {
			this.modal = false
		},
	},
}
</script>

<style lang="scss" scoped>
@import '../../assets/variables.scss';
@import '../../assets/avatar.scss';
@include avatar-mixin(64px);
@include avatar-mixin(128px);

.device-checker {
	width: 400px;
	background-color: var(--color-main-background);
	&__preview {
		position: relative;
		width: 250px;
		margin: auto;
	}

	&__selectors {
		width: 100%;
	}
}

.preview {
	&__video {
		width: 100%;
	}

	&__novideo {
		width: 100%;
	}
}
</style>
