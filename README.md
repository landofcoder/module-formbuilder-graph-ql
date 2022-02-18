# Mage2 Module Lof FormbuilderGraphQl

    ``landofcoder/module-formbuilder-graph-ql``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
magento 2 formbuilder graphql extension

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Lof`
 - Enable the module by running `php bin/magento module:enable Lof_FormbuilderGraphQl`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require landofcoder/module-formbuilder-graph-ql`
 - enable the module by running `php bin/magento module:enable Lof_FormbuilderGraphQl`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Queries

1. Get Form design by Id

```
query {
    lofFormBuilderFormById (form_id: Int!) {
        title
        identifier
        show_captcha
        show_toplink
        submit_button_text
        redirect_link
        creation_time
        before_form_content
        after_form_content
        success_message
        page_title
        meta_keywords
        meta_description
        submit_text_color
        submit_background_color
        submit_hover_color
        input_hover_color
        tags
        design {
            cid
            label
            field_type
            required
            field_options
            fieldcol
            wrappercol
            inline_css
            field_size
            font_weight
            color_text
            font_size
            color_label
            validation
            include_blank_option
            options {
                label
                checked
            }
        }
        stores
    }
}
```





