#!/bin/bash

set -e

root=$(dirname "$(wp config path)")

rm -rf "${root}"/env/wordpress.*.xml

wp export --dir="${root}/env/" --post_type=post --filename_format=wordpress.post.xml
wp export --dir="${root}/env/" --post_type=page --filename_format=wordpress.page.xml
wp export --dir="${root}/env/" --post_type=attachment --filename_format=wordpress.attachment.xml

wp post-type list --_builtin=0 --can_export=1 --format=csv | tail +2 | while read -r line; do
  post_type="$(echo "${line}" | cut -d "," -f 1)"
  wp export --dir="${root}/env/" --post_type="${post_type}" --filename_format="wordpress.${post_type}.xml"
done

rm -rf "${root}/env/uploads/"
cp -r "${root}/wp-content/uploads/." "${root}/env/uploads/"
