<?php
namespace Payum\Klarna\Invoice\Action\Api;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Klarna\Invoice\Request\Api\PopulateKlarnaFromDetails;

class PopulateKlarnaFromDetailsAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     *
     * @param PopulateKlarnaFromDetails $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());
        $klarna = $request->getKlarna();

        if ($details['articles']) {
            foreach ($details['articles'] as $article) {
                $article = ArrayObject::ensureArrayObject($article);

                $klarna->addArticle(
                    utf8_decode($article['qty']),
                    utf8_decode($article['artNo']),
                    utf8_decode($article['title']),
                    utf8_decode($article['price']),
                    utf8_decode($article['vat']),
                    utf8_decode($article['discount']),
                    utf8_decode($article['flags'])
                );
            }
        }

        if ($details['shipping_address']) {
            $address = ArrayObject::ensureArrayObject($details['shipping_address']);

            $klarna->setAddress(\KlarnaFlags::IS_SHIPPING, new \KlarnaAddr(
                utf8_decode($address['email']),
                utf8_decode($address['telno']),
                utf8_decode($address['cellno']),
                utf8_decode($address['fname']),
                utf8_decode($address['lname']),
                utf8_decode($address['careof']),
                utf8_decode($address['street']),
                utf8_decode($address['zip']),
                utf8_decode($address['city']),
                utf8_decode($address['country']),
                utf8_decode($address['house_number']),
                utf8_decode($address['house_extension'])
            ));
        }

        if ($details['billing_address']) {
            $address = ArrayObject::ensureArrayObject($details['billing_address']);

            $klarna->setAddress(\KlarnaFlags::IS_BILLING, new \KlarnaAddr(
                utf8_decode($address['email']),
                utf8_decode($address['telno']),
                utf8_decode($address['cellno']),
                utf8_decode($address['fname']),
                utf8_decode($address['lname']),
                utf8_decode($address['careof']),
                utf8_decode($address['street']),
                utf8_decode($address['zip']),
                utf8_decode($address['city']),
                utf8_decode($address['country']),
                utf8_decode($address['house_number']),
                utf8_decode($address['house_extension'])
            ));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof PopulateKlarnaFromDetails;
    }
}