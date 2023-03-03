export class Attributer {
    /**
     * @param {jQuery} buttonNode
     *
     * @returns number|null
     */
    static getPostId(buttonNode) {
        const { postId } = buttonNode.dataset;

        return postId !== undefined ? parseInt(postId): null;
    }

    /**
     * @returns number
     */
    static getPostLikesCount() {
        return parseInt(Attributer.getPostCounter().text());
     }

    /**
     * @returns {jQuery}
     */
    static getPostCounter() {
        const counter = $('.post-likes-count');

        if (counter === undefined) {
            throw new Error('post-likes-count cannot be undefined');
        }

        return counter;
    }

    /**
     * @returns void
     */
    static updatePostLikesCount(isAdded) {
        Attributer.getPostCounter().text(this.getPostLikesCount() + isAdded ? 1 : -1);
     }

    /**
     * @param {jQuery} buttonNode
     *
     * @returns boolean
     */
    static isLiked(buttonNode) {
        return buttonNode.dataset.isLiked === '1'
    }

    /**
     * @param {jQuery} buttonNode
     *
     * @returns boolean
     */
    static likePost(buttonNode) {
        buttonNode.dataset.isLiked = '1';
     }

    /**
     * @param {jQuery} buttonNode
     *
     * @returns void
     */
    static removeIsLiked(buttonNode) {
        buttonNode.dataset.isLiked = '0';
     }
}