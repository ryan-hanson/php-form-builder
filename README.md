# PHP Form Builder

Provides a just-add-water PHP solution for website form creation and validation.

[Demo](http://dev.ryan-hanson.com/formbuilder/demo.php)

## Classes

- formbuilder.class.php (extends validation class) - core class used to generate the form 
- validation.class.php (extends message class) - class for validating user input
- message.class.php (standalone) - class for displaying messages

## Basic Usage

Once you include the three files above you can create a form using the code below.

```php
$form = new FormBuilder();
$form->addField( array('name' => 'First Name', 'type' => 'name', 'required'=>1) ); 
$select_options = array('male'=>'Male', 'female'=>'Female'); 
$form->addField( array('name' => 'Gender', 'type' => 'select', 'required' => 1, 'options' => $select_options ) ); 
$form->addField( array('name' => 'Email', 'type' => 'email', 'required'=>1) );  
$form->addField( array('name' => 'Message', 'type' => 'textarea') );
$form->createForm());
```

This code will generate the following HTML.

```html
<form method="post" id="MMplJan18g" class="formBuilder">
	<p>
		<label class="required">First Name</label><br />
		<input type="text" name="first_name" value="" class="name required" />
	</p>
	<p>
		<select name="gender" class="required">
			<option value="male" SELECTED>Male</option>
			<option value="female">Female</option>
		</select>
	</p>
	<p>
		<label class="required">Email</label><br />
		<input type="text" name="email" value="" class="email required" />
	</p>
	<p>
		<label>Message</label><br />
		<textarea name="message"></textarea>
	</p>
	<div class="clear"></div>
	<input type="hidden" name="submit_form" value="1" />
	<input type="submit" value="Submit"  />
</form>
```
## Adding Fields to the Form

The above example added 3 fields to the form using the `addField()` method. This method accepts an array of settings. The following settings can be passed.

- _name_ - field label
- _key_ - name attribute
- _type_ [default:'text'] - determines input and validation type (options: 'name', 'text', 'email', 'url', 'phone', 'date', 'alpha', 'numeric', 'alphanumeric', 'alphanumbericdash', 'integer', 'float', 'creditcard', 'textarea', 'select') 
- _required_ [default:0] - boolean to make an input required
- _options_ [default:NULL] - option array to use as value/name pairs for the select menu
- _autofill_ [default:1] - boolean to determine whether the input will be filled in automatically with original input (used for sensitive data)
- break: boolean for creating a break between fields

## Generating the Form

To generate the form the `createForm()` method must be called. This method also accepts an array of settings.

- _method_ [default:'post'] - form method for submitting data (options: 'post', 'get')
- _submit_value_ [default:'Submit'] - used as the value for the submit button
- _submit_key_ [default:'submit_form'] - used as the name key for detecting form submission
- _ajax_ [default:0] - boolean for whether to use client side validation
- _wrapper_ [default:1] - boolean for whether a wrapper will be used around each field
- _wrapperStart_ [default:'&#60;p&#62;'] - opening tag for field wrapper
- _wrapperEnd_ [default:'&#60;&#47;p&#62;'] - closing tag for field wrapper

## Using Ajax Validation

To validate the form asynchronously be sure to include the following files.

- ajax/validate.php
- js/validate.jquery.js 

