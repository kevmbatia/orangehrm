
<div class="outerbox">
    <div class="mainHeading"><h2><?php echo $title; ?></h2></div>

    <div class="actionbar">
        <div class="actionbuttons">
            <?php
            foreach ($buttons as $key => $buttonProperties) {
                $button = new Button();
                $button->setProperties($buttonProperties);
                $button->setIdentifier($key);
                echo $button->__toString(), "\n";
            }
            ?>
        </div>
        <br class="clear" />
    </div>

    <table style="border-collapse: collapse; width: 100%; text-align: left;" class="data-table">
        <thead>
            <tr>
                <?php
                if ($hasSelectableRows) {
                    $selectAllCheckobx = new Checkbox();
                    $selectAllCheckobx->setProperties(array(
                        'id' => 'ohrmList_chkSelectAll',
                        'name' => 'chkSelectAll'
                    ));
                    $selectAllCheckobx->setIdentifier('Select_All');
                    echo content_tag('th', $selectAllCheckobx->__toString());
                }
                ?>

                <?php
                foreach ($columns as $header) {
                    if ($header->isSortable()) {
                        $nextSortOrder = ($header->getSortOrder() === 'ASC') ? 'DESC' : 'ASC';
                        $sortOrderStyle = ($header->getSortOrder() == '') ? 'null' : $header->getSortOrder();

                        $actionName = sfContext::getInstance()->getActionName();

                        $sortUrl = 'index.php/' .
                                sfContext::getInstance()->getModuleName() . '/' .
                                $actionName . '/' .
                                'sortField/' . 'field' . '/' .
                                'sortOrder/' . $nextSortOrder;

                        $request = sfContext::getInstance()->getRequest();
                        if ($request->isMethod('post') && $request->getParameter('cmbSearchBy', null) !== null) {
                            $searchBy = $request->getParameter('cmbSearchBy');
                            $searchFor = $request->getParameter('txtSearchFor');
                            $sortUrl .= '/isSearch/yes' .
                                    '/searchBy/' . $request->getParameter('cmbSearchBy') .
                                    '/searchFor/' . $request->getParameter('txtSearchFor');
                        }

                        $headerCell = new SortableHeaderCell();
                        $headerCell->setProperties(array(
                            'label' => __($header->getName()),
                            'sortUrl' => $sortUrl,
                            'currentSortOrder' => $sortOrderStyle,
                        ));
                    } else {
                        $headerCell = new HeaderCell();
                        $headerCell->setProperties(array(
                            'label' => __($header->getName()),
                            )
                        );
                    }
                ?>
                    <th><?php echo $headerCell->__toString(); ?></th>
                <?php } ?>
            </tr>
        </thead>

        <tbody>
            <?php
                if ($data->count() > 0) {
                    $rowCssClass = 'even';

                    foreach ($data as $object) {
                        $idValue = $object->$idValueGetter();
                        $rowCssClass = ($rowCssClass === 'odd') ? 'even' : 'odd';
            ?>
                        <tr class="<?php echo $rowCssClass; ?>">
                <?php
                        if ($hasSelectableRows) {
                            if (false) { /* in_array($idValue, $unselectableRowIds) */
                                $selectCellHtml = '&nbsp;';
                            } else {
                                $selectCheckobx = new Checkbox();
                                $selectCheckobx->setProperties(array(
                                    'id' => "ohrmList_chkSelectRecord_{$idValue}",
                                    'value' => $idValue,
                                    'name' => 'chkSelectRow[]'
                                ));

                                $selectCellHtml = $selectCheckobx->__toString();
                            }

                            echo content_tag('td', $selectCellHtml);
                        }

                        foreach ($columns as $header) {
                            $cellHtml = '';
                            $cellClass = ucfirst($header->getElementType()) . 'Cell';
                            $properties = $header->getElementProperty();

                            $cell = new $cellClass;
                            $cell->setProperties($properties);
                            $cell->setDataObject($object);
                ?>
                            <td><?php echo $cell->__toString(); ?></td>
                <?php
                        }
                ?>
                    </tr>
            <?php
                    }
                } else {
                    $colspan = count($columns);
                    if ($hasSelectableRows) {
                        $colspan++;
                    }
            ?>
                    <tr>
                        <td colspan="<?php echo $colspan; ?>"><?php echo __('No records to display'); ?></td>
                    </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    /* FIXME: This script was added to preseve existing functionality */
    $(document).ready(function() {
        $('.data-table tbody tr').hover(function() {  // highlight on mouse over
            $(this).removeClass();
            $(this).addClass("trHover");
        });

        $('.data-table tbody tr').mouseout(function() { // redraw table raws with alternate colors
           var even = true;
           $('.data-table tbody tr').each(function() {
               $(this).addClass((even) ? 'odd' : 'even');
               even = !even;
            });
        });
    });
</script>