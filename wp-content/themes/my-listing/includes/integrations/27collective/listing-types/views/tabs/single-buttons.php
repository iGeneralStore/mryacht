<h3 class="section-title">
	Add cover buttons
	<span class="subtitle">Not sure what's this? <a href="https://27collective.net/files/mylisting/docs/#listings-single" target="_blank">View the docs</a>. To edit a button, click on it.</span>
</h3>

<draggable v-model="single.buttons" :options="{group: 'single-buttons', animation: 100, filter: '.no-drag'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
	<div v-for="button in single.buttons" class="field" @click="state.single.active_button = button" :class="state.single.active_button == button ? 'active' : ''">
		<i :class="button.icon"></i> {{ formatLabel(button.label, button.custom_field) }}
	</div>

	<a class="btn btn-primary add-new no-drag" @click.prevent="addCoverButton">
		+ Add New
	</a>
</draggable>

<div class="edit-cover-button" v-if="state.single.active_button">
	<h5>Edit button</h5>
	<div class="cover-button-wrapper">
		<div class="cover-button-options">
			<div class="form-group">
				<label>Button Icon</label>
				<iconpicker v-model="state.single.active_button.icon"></iconpicker>
			</div>
			<div class="form-group">
				<label>Label <small v-if="state.single.active_button.action == 'custom-field'">Use [[field]] to get the contents of the custom field.</small></label>
				<input type="text" v-model="state.single.active_button.label">

				<?php c27()->get_partial('admin/input-language', ['object' => 'state.single.active_button.label_l10n']) ?>
			</div>
			<div class="form-group">
				<label>Action</label>
				<div class="select-wrapper">
					<select v-model="state.single.active_button.action">
						<?php foreach ($cover_buttons as $button): ?>
							<option value="<?php echo esc_attr( $button['action'] ) ?>"><?php echo esc_attr( $button['label'] ) ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group" v-if="state.single.active_button.action == 'custom-field'">
				<label>Select Field</label>
				<div class="select-wrapper">
					<select v-model="state.single.active_button.custom_field">
						<option v-for="field in cover_button_fields" :value="field.slug">{{ field.label }}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label>Button Style</label>
				<div class="select-wrapper">
					<select v-model="state.single.active_button.style">
						<option value="primary">Primary</option>
						<option value="secondary">Secondary</option>
						<option value="outlined">Outlined</option>
						<option value="plain">Plain</option>
					</select>
				</div>
			</div>
			<div class="footer form-group">
				<label>&nbsp;</label>
				<a @click.prevent="state.single.active_button = null" class="btn btn-primary btn-xs">Save</a>
				<a @click.prevent="deleteCoverButton(state.single.active_button)" class="btn btn-plain btn-xs"><i class="mi delete"></i>Delete button</a>
			</div>

			<div style="clear: both;"></div>
		</div>
	</div>
</div>