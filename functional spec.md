Function spec
=============

PHP

  init()
    - Check if installation has been performed, install('status')
      - 'incomplete': call install('setup')
      - 'complete': check if settings have been supplied, settings('check')
        - FALSE: settings missing, call settings('error','missing')
        - TRUE: set scheduled task to check if DOIs have been successfully 
          registered every X hours, check_status()

    - Catch URL parameters
      ?a=register&id=[post ID]



  install()
    
    Methods:

      'setup'
        - Create settings table in database w/ following info
          - DOI (default: null)
          - DOI suffix (default: null)
          - CrossRef username (default: null)
          - CrossRef password (default: null)
          - Last DOI check (default: null)
        - Create post table (empty)

      'status'
        - returns 'complete' if settings and post tables exist
        - returns 'incomplete' if either table not created



  settings()

    Methods: 

      'check'
        - Check to see if any settings in settings database === null
          - TRUE: settings missing, return false
          - FALSE: settings suppliled, return true

      'error'
        'missing'
          - Inserts a warning message at the top of all admin pages telling user to fill out 
            WP-DOI settings before



  register()

    - Check if DOI exists in database already: db('doi_exists',[post_id])
      - TRUE: refresh page, display error message
      - FALSE:
        - Generate DOI
          - DOI + DOI suffix + [post id]
        - Insert post info into post database, db('insert_doi',[post_id],[generated doi])


  db()

    Methods:

      'insert_doi', requires 'post_id' and [generated doi]
        - Insert the post into the post database, including:
          - post_id
          - generated doi
          - status => 'submitted'

      'change_doi_status', requires [new_status]
        - If status != [new_status]
          - Change status to [new_status]

      'doi_exists'
          - Check if a DOI exists in the database
            - TRUE: return true
            - FALSE: return false



  check_status() - Performed every X hours by wp cron task

    - Loop through post database, create array of



JS

  register()
    - Display confirmation modal
    - On click "OKAY"
      - wp-doi.php?a=register&post_id=[post_id]




File Structure
==============

- wp-doi
    - wp-doi.php
    - includes
      - wp-doi-core.php
      - install.php
      - settings.php
      - register.php
