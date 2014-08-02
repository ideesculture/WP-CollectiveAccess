#!/bin/bash

#
# This script allows a fast sync from github to wordpress.org svn
# This is an adaptation of deanc script : https://github.com/deanc/wordpress-plugin-git-svn
#

# args
MSG=${1-'Deploy from git'}
BRANCH=${2-'trunk'}

SLUG=wp-collectiveaccess
SRC_DIR=~/dev/wordpress/wp-content/plugins/WP-CollectiveAccess
SVN_DIR=~/dev/wp-collectiveaccess/
DEST_DIR=$SVN_DIR/$BRANCH

# make sure we're deploying from the right dir
if [ ! -d "$SRC_DIR/.git" ]; then
	echo "$SRC_DIR doesn't seem to be a git repository"
	exit
fi

# make sure the SVN repo exists
if [ ! -d "$SVN_DIR" ]; then
	echo "Coudn't find the SVN repo at $SVN_DIR. Trying to create one..."
	svn co http://plugins.svn.wordpress.org/$SLUG/ $SVN_DIR
	exit
fi

# make sure the destination dir exists
mkdir -p $DEST_DIR
svn add $DEST_DIR 2> /dev/null

# delete everything except .svn dirs
for file in $(find $DEST_DIR/* -type f -and -not -path "*.svn*")
do
	rm $file
done

git checkout -f master

# copy everything over from git
rsync --recursive --exclude='*.git*' $SRC_DIR/* $DEST_DIR

# check .svnignore
for file in $(cat "$SRC_DIR/.svnignore" 2> /dev/null)
do
	rm -rf $DEST_DIR/$file
done

cd $DEST_DIR

# svn addremove
svn stat | awk '/^\?/ {print $2}' | xargs svn add > /dev/null 2>&1
svn stat | awk '/^\!/ {print $2}' | xargs svn rm --force

svn stat

svn ci -m "$MSG"

