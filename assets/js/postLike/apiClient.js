'use strict';

import * as routingData from '../../../public/js/fos_js_routes.json';
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import { IsNotAuthorizedException } from './exceptions/IsNotAuthorizedException';
import { BadRequestException } from './exceptions/BadRequestException.js';

Routing.setRoutingData(routingData.default);

export const ApiClient = {
    /**
     * @param {string} postId
     */
    async likePost(postId) {
        const response = await fetch(Routing.generate('like_post', { postId }), { method: 'POST', credentials: 'include' });

        if (response.status === 302) {
            throw new IsNotAuthorizedException('You need to sign in');
        }

        return response.json();
    },
    /**
     * @param {string} postId
     */
    async unlikePost(postId) {
        const response = await fetch(Routing.generate('unlike_post', { postId }), { method: 'DELETE', credentials: 'include' });

        if (response.status === 302) {
            throw new IsNotAuthorizedException('You need to sign in');
        }

        const json = await response.json();

        if (response.status === 400) {
            throw new BadRequestException(json.message);
        }

        return json;
    }
};
