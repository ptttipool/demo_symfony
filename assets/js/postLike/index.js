import { ApiClient } from './apiClient.js';
import { Attributer } from './atributer';

(function ($) {
    'use strict';

    $(document).on('click', '.like-review', function () {
        const postId = Attributer.getPostId(this);
        const isLiked = Attributer.isLiked(this);

        if (postId === null) {
            throw new Error('post id cannot be undefined');
        }

        isLiked === false ? likePost(this, postId) : unlikePost(this, postId);
    });

    /**
     * @param {jQuery} buttonNode
     * @param {string} postId
     */
    async function likePost(buttonNode, postId) {
        try {
            buttonNode.disabled = true;
            await ApiClient.likePost(postId);

            Attributer.likePost(buttonNode);
            Attributer.updatePostLikesCount(true);
            $(buttonNode).html('<i class="fa fa-heart" aria-hidden="true"></i> You liked this');
        } catch (error) {
            console.log(error);
        }

        buttonNode.disabled = false;
    }

    /**
     * @param {jQuery} buttonNode
     * @param {string} postId
     */
    async function unlikePost(buttonNode, postId) {
        try {
            buttonNode.disabled = true;
            await ApiClient.unlikePost(postId);

            Attributer.removeIsLiked(buttonNode);
            Attributer.updatePostLikesCount(false);
            $(buttonNode).html('<i class="fa fa-heart" aria-hidden="true"></i> Like');
        } catch (error) {
            console.log(error);
        }

        buttonNode.disabled = false;
    }
})((window.jQuery));