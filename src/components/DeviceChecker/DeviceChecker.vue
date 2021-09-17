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
			<!-- Preview -->
			<div class="device-checker__preview">
				<Video v-if="videoPreviewAvailable"
					class="preview__video"
					disable-picture-in-picture="true"
					tabindex="-1" />
				<div v-if="!videoPreviewAvailable"
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

			<!--
				Toggle audio and video on and off before starting or joining
				a call.
			-->
			<div class="device-checker__call-preferences">
				<!-- Audio toggle -->
				<button
					class="device-toggle"
					@click="toggleAudio">
					<span class="device-toggle__icon">
						<Microphone
							v-if="audioOn"
							title=""
							:size="20" />
						<MicrophoneOff
							v-if="!audioOn"
							title=""
							:size="20" />
					</span>
				</button>

				<!-- Video toggle -->
				<button
					class="device-toggle"
					@click="toggleVideo">
					<span class="device-toggle__icon">
						<Video
							v-if="videoOn"
							title=""
							:size="20" />
						<VideoOff
							v-if="!videoOn"
							title=""
							:size="20" />
					</span>
				</button>
			</div>

			<!-- Device selection -->
			<div class="device-checker__device-selection">
				<button v-if="!showDeviceSelection"
					class="select-devices"
					@click="showDeviceSelection = true">
					<span class="select-devices__icon">
						<Cog
							title=""
							:size="20" />
					</span>
					<span> {{ t('spreed', 'Choose devices') }}</span>
				</button>
				<template v-if="showDeviceSelection">
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
				</template>
			</div>

			<!-- Join call -->
			<CallButton />
		</div>
	</Modal>
</template>

<script>
import Modal from '@nextcloud/vue/dist/Components/Modal'
import { devices } from '../../mixins/devices'
import MediaDevicesSelector from '../MediaDevicesSelector.vue'
import VideoBackground from '../CallView/shared/VideoBackground.vue'
import Avatar from '@nextcloud/vue/dist/Components/Avatar'
import Cog from 'vue-material-design-icons/Cog.vue'
import Microphone from 'vue-material-design-icons/Microphone'
import MicrophoneOff from 'vue-material-design-icons/MicrophoneOff'
import Video from 'vue-material-design-icons/Video'
import VideoOff from 'vue-material-design-icons/VideoOff'
import { localMediaModel } from '../../utils/webrtc/index'
import { audioVideoToggles } from '../../mixins/audioVideoToggles'
import CallButton from '../TopBar/CallButton.vue'

export default {
	name: 'DeviceChecker',

	components: {
		Modal,
		MediaDevicesSelector,
		VideoBackground,
		Avatar,
		Cog,
		Microphone,
		MicrophoneOff,
		Video,
		VideoOff,
		CallButton,
	},

	mixins: [devices, audioVideoToggles],

	data() {
		return {
			model: localMediaModel,
			modal: true,
			showDeviceSelection: false,
			audioOn: undefined,
			videoOn: undefined,
		}
	},

	computed: {
		displayName() {
			return this.$store.getters.getDisplayName()
		},

		userId() {
			return this.$store.getters.getUserId()
		},

		token() {
			return this.$store.getters.getToken()
		},
	},

	mounted() {
		this.audioOn = !localStorage.getItem('audioDisabled_' + this.token)
		this.videoOn = !localStorage.getItem('videoDisabled_' + this.token)
	},

	methods: {
		showModal() {
			this.modal = true
		},

		closeModal() {
			this.modal = false
		},

		toggleAudio() {
			if (!this.audioOn) {
				localStorage.setItem('audioDisabled_' + this.token, 'false')
				this.audioOn = true
			} else {
				localStorage.setItem('audioDisabled_' + this.token, 'true')
				this.audioOn = false
			}
		},

		toggleVideo() {
			if (!this.videoOn) {
				localStorage.setItem('videoDisabled_' + this.token, 'false')
				this.videoOn = true
			} else {
				localStorage.setItem('videoDisabled_' + this.token, 'true')
				this.videoOn = false
			}
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
	padding: 20px;
	background-color: var(--color-main-background);
	&__preview {
		position: relative;
		width: 250px;
		margin: 0 auto 12px auto;
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
		border-radius: 12px;
	}

	&__device-selection {
		width: 100%;
	}
}

.preview {
	&__video {
		width: 100%;
	}

	&__novideo {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 200px;
		width: 360px;
		overflow: hidden;
	}
}

.select-devices {
	display: flex;
	align-items: center;
	justify-content: center;
	background: none;
	border: none;
	&__icon {
		margin-right: 4px;
	}
	opacity: 0.8;
	&:hover,
	&:focus {
		opacity: 1;
	}
}

.device-toggle {
	background: none;
	border: none;
}
</style>
