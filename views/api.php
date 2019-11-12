<?php

if (isset($_REQUEST['api'])) {
    switch ($_REQUEST['api']) {
            /**
         * CREATE USER
         */
        case 'create_user':
            if (check('name') && check('password') && check('email') && check('afiliacja')) {
                User::create($_REQUEST['name'], $_REQUEST['password'], $_REQUEST['email'], $_REQUEST['afiliacja']);
            } else {
                die("Błędne dane!");
            }
            break;

            /**
             * READ USER
             */
        case "read_user":
            if (isset($_REQUEST['email'])) {
                echo json_encode(User::read("email = '" . $_REQUEST['email'] . "'"));
            } else {
                echo json_encode(User::read());
            }
            break;

            /**
             * UPDATE USER
             */
        case "update_user":
            if (check("name") && check("data")) {
                $id = $_REQUEST['id'];
                $data = json_decode($_REQUEST['data']);
                User::update($id, $data);
            } else {
                die("Błędne dane!");
            }
            break;

            /**
             * DELETE USER
             */
        case "delete_user":
            $id = check("id");
            if ($email || $id) {
                User::delete("id = '" . $_REQUEST['id'] . "'");
            } else {
                die("Please specify user email or id!");
            }
            break;

            // -----------------------------------------
            // -----------------------------------------
            // -----------------------------------------

            /**
             * CREATE PUBLICATION
             */
            // case 'create_publication':
            //     if (
            //         check('title') &&
            //         check('authors') &&
            //         check('shares') &&
            //         check('points') &&
            //         check('magazine') &&
            //         check('url')
            //     ) {
            //         Publication::save_publication($_REQUEST['title'], $_REQUEST['authors'], $_REQUEST['shares'], $_REQUEST['points'], $_REQUEST['magazine'], $_REQUEST['url']);
            //     } else {
            //         die("Błędne dane!");
            //     }
            //     break;

            /**
             * READ PUBLICATION
             */
        case 'read_publications':
            if (check('title')) {
                echo json_encode(Publication::get_publication($_REQUEST['title']));
            } else {
                echo json_encode(Publication::get_publication());
            }
            break;

            /**
             * UPDATE PUBLICATION
             */
        case "update_publication":
            if (check('id')) {
                $id = $_REQUEST['id'];
                $title = check('title') ? $_REQUEST['title'] : null;
                $authors = check('authors') ? $_REQUEST['authors'] : null;
                $shares = check('shares') ? $_REQUEST['shares'] : null;
                $points = check('points') ? $_REQUEST['points'] : null;
                $magazine = check('magazine') ? $_REQUEST['magazine'] : null;
                $url = check('url') ? $_REQUEST['url'] : null;

                $data = [
                    "title" => $title,
                    "authors" => $authors,
                    "shares" => $shares,
                    "points" => $points,
                    "magazine" => $magazine,
                    "url" => $url
                ];

                $data = array_filter($data, function ($el) {
                    return $el !== null;
                });

                var_dump($data["shares"]);
                Publication::update_publication($id, $data);
            } else {
                die("Błędne dane!");
            }
            break;

            /**
             * DELETE PUBLICATION
             */
        case "delete_publication":
            if (check('id')) {
                echo Publication::delete_publication($_REQUEST['id']);
            } else {
                echo "Unable to delete publication without ID!";
            }
            break;
    }
}
