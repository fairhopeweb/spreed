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
			<video v-show="videoPreviewAvailable"
				ref="video"
				disablePictureInPicture="true"
				tabindex="-1" />
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
	</Modal>
</template>

<script>
import Modal from '@nextcloud/vue/dist/Components/Modal'
import { devices } from '../../mixins/devices'
import MediaDevicesSelector from '../MediaDevicesSelector.vue'

export default {
	name: 'DeviceChecker',

	components: {
		Modal,
		MediaDevicesSelector,
	},

	mixins: [devices],

	data() {
		return {
			modal: true,
		}
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
.device-checker {
	height: 400px;
	width: 400px;
	background-color: var(--color-main-background);
}
</style>
