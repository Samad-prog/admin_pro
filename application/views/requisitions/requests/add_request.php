<section class="columns is-gapless mb-0 pb-0">
	<div class="column is-narrow is-fullheight" id="custom-sidebar">
		<?php $this->view('requisitions/commons/sidebar'); ?>
	</div>
	<div class="column">
		<div class="columns">
			<div class="column section py-5">
				<div class="columns">
					<div class="column">
						<?php $this->view('requisitions/commons/breadcrumb'); ?>
					</div>
				</div>
				<div class="columns">
					<div class="column is-hidden-print">
						<form action="<?= base_url('requisitions/search_request'); ?>" method="get">
							<div class="field has-addons">
								<div class="control has-icons-left is-expanded">
									<input class="input is-small is-fullwidth" name="search" id="myInput" type="search"
										placeholder="Search Request"
										value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" required>
									<span class="icon is-small is-left">
										<i class="fas fa-search"></i>
									</span>
								</div>
								<div class="control">
									<button class="button is-small" type="submit"><span class="icon is-small">
											<i class="fas fa-arrow-right"></i>
										</span>
									</button>
								</div>
							</div>
						</form>
					</div>
					<div class="column is-hidden-touch is-narrow">
						<div class="field has-addons">
							<p class="control">
								<a href='<?= base_url('requisitions/request_list'); ?>'
									class="button is-small <?= isset($add_page) ? 'has-background-primary-light' : '' ?>">
									<span class="icon is-small">
										<i class="fas fa-list"></i>
									</span>
									<span>Request List</span>
								</a>
							</p>
							<p class="control">
								<a href="<?= base_url("requisitions/add_request") ?>"
									class="button is-small <?= (isset($addRequestPage)) ? 'has-background-primary-light' : '' ?>">
									<span class="icon is-small">
										<i class="fas fa-plus"></i>
									</span>
									<span>Add Request</span>
								</a>
							</p>
						</div>
					</div>
				</div>

				<?php if($this->session->flashdata('success')) : ?>
				<div class="columns">
					<div class="column">
						<div class="notification is-success is-light">
							<button class="delete is-small"></button>
							<div class="columns is-vcentered">
								<div class="column is-size-7">
									<i class="fas fa-check pr-1"></i>
									<?= $message = $this->session->flashdata('success'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php elseif($this->session->flashdata('failed')) : ?>
				<div class="columns">
					<div class="column">
						<div class="notification is-danger is-light">
							<button class="delete is-small"></button>
							<div class="columns is-vcentered">
								<div class="column is-size-7">
									<i class="fas fa-exclamation pr-1"></i>
									<?= $message = $this->session->flashdata('failed'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif ?>
				<form
					action="<?= empty($edit) ? base_url('requisitions/save_request') : base_url('requisitions/edit_request') ?>"
					method="POST">
					<input type="hidden" value="<?php echo $this->uri->segment(3); ?>">
					<div class="columns">
						<div class="column">
							<fieldset>
								<div class="field">
									<label class="label is-small">Employee Name <span
											class="has-text-danger">*</span></label>
									<div class="control has-icons-left">
										<input type="text" class="input is-small"
											value="<?= $this->session->userdata('fullname') ?>" type="text"
											placeholder="e.g John Doe" required disabled>
										<span class="icon is-small is-left">
											<i class="fas fa-user-tie"></i>
										</span>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="column">
							<fieldset>
								<div class="field">
									<label class="label is-small">Employee Code <span
											class="has-text-danger">*</span></label>
									<div class="control has-icons-left">
										<input type="text" class="input is-small"
											value="S2S-<?= $this->session->userdata('id') ?>" type="text"
											placeholder="e.g S2S-123" required disabled>
										<span class="icon is-small is-left">
											<i class="fas fa-signature"></i>
										</span>
									</div>
								</div>
							</fieldset>
						</div>
					</div>

					<div class="columns">
						<div class="column">
							<div class="control has-icons-left">
								<label class="label is-small">Location <span class="has-text-danger">*</span></label>
								<div class="select is-small is-fullwidth">
									<select required disabled>
										<option value="">Select a City</option>
										<?php foreach ($locations as $data) : ?> 
											<option value="<?= $data->id ?>" <?= $data->id == $this->session->userdata('location') ? 'selected' : '' ?>><?= $data->name ?></option>
										<?php endforeach ?>
									</select>
									<input type="hidden" name="location" value="<?= $this->session->userdata('location'); ?>">
								<span class="icon is-small is-left">
									<i class="fas fa-street-view"></i>
								</span>
								</div>
							</div>
						</div>
						<div class="column">
							<div class="control">

								<fieldset>
									<div class="field">
										<label class="label is-small">Requisition Date <span
												class="has-text-danger">*</span></label>
										<div class="control has-icons-left">
											<input type="date" class="input is-small"
												value="<?= date("Y-m-d") ?>" required disabled>
											<span class="icon is-small is-left">
												<i class="fas fa-envelope"></i>
											</span>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
					</div>


					<div class="columns">
                    <div class="column">
							<div class="control has-icons-left">
								<label class="label is-small">Department <span class="has-text-danger">*</span></label>
								<div class="select is-small is-fullwidth">
									<select required disabled>
										<option selected disabled value="">Select a Department</option>
										<?php foreach ($departments as $data) : ?>
											<option value="<?= $data->id ?>" <?= $data->id == $this->session->userdata('department') ? 'selected' : '' ?>><?= $data->department ?></option>
										<?php endforeach ?>
									</select>
									<input type="hidden" name="department" value="<?= $this->session->userdata('department'); ?>">
									
								<span class="icon is-small is-left">
									<i class="fas fa-street-view"></i>
								</span>
								</div>
							</div>
						</div>		
						<div class="column">
							<div class="control has-icons-left">
								<label class="label is-small">Company <span class="has-text-danger">*</span></label>
								<div class="select is-small is-fullwidth">
									<select required disabled>
										<option selected disabled value="">Select a Company</option>
										<?php foreach ($companies as $data) : ?>
											<option value="<?= $data->id ?>" <?= $data->id == $this->session->userdata('company_id') ? 'selected' : '' ?>><?= $data->name ?></option>
										<?php endforeach ?>
									</select>
									<input type="hidden" name="company" value="<?= $this->session->userdata('company_id'); ?>">
								    <span class="icon is-small is-left">
									<i class="fas fa-street-view"></i>
								    </span>
								</div>
							</div>
						</div>
					</div>
					<hr>
                    <div class="columns">
						<div class="column">
							<div class="columns"> 
								<div class="column">
								<label for="">Particular</label>
									<input type="text" name="particular[]" class="input is-small" placeholder="Particular" required>
								</div>
								<div class="column">
								<label for="">Quantity</label>
									<input type="number" name="quantity[]" class="input is-small" placeholder="Quantity" min="1" max="10" required>
								</div>
							</div>
						</div>
					</div>  
					<hr>
					<div class="columns">
						<div class="column">
							<fieldset>
								<div class="field">
									<label class="label is-small">Reason for Purchase </label>
									<div class="control">
										<textarea name="reason" class="textarea is-small" placeholder="Please enter brief description explaining why you are requesting the item(s)." rows="4" required></textarea>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="columns">
						<div class="column has-text-right">
							<div class="buttons is-pulled-right">
								<?php if(!isset($edit_item)): ?>
								<button class="button is-danger is-small is-outlined" type="reset">Reset Form</button>
								<?php endif ?>
								<p class="control">
									<button class="button is-small is-success" type="submit">
										<span><?= !isset($edit_item) ? 'Save and continue' : 'Save Changes' ?></span>
										<span class="icon is-small">
											<i class="fas fa-arrow-right"></i>
										</span>
									</button>
								</p>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<script>


</script>