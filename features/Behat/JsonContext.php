<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Features\App;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert as a;

class JsonContext extends AbstractMinkContext
{

    /**
     * @Given /^the following response properties exist:$/
     */
    public function theResponsePropertiesExist(PyStringNode $properties)
    {
        foreach (explode("\n", (string)$properties) as $property) {
            $this->thePropertyExists($property);
        }
    }

    /**
     * @Given /^the "([^"]*)" property should exist$/
     */
    public function thePropertyExists($property)
    {
        $payload = $this->getResponseBody();

        $message = sprintf(
            'Asserting the [%s] property exists in: %s',
            $property,
            json_encode($payload)
        );

        a::assertTrue($this->hasProperty($payload, $property), $message);
    }

    /**
     * @Given /^the "([^"]*)" property should not exist$/
     */
    public function thePropertyNotExists($property)
    {
        $payload = $this->getResponseBody();

        $message = sprintf(
            'Asserting the [%s] property does not exist in: %s',
            $property,
            json_encode($payload)
        );

        a::assertFalse($this->hasProperty($payload, $property), $message);
    }

    /**
     * @Given /^the "([^"]*)" link should exist$/
     */
    public function theLinkExists($link)
    {
        $this->thePropertyExists('_links.'.$link);
    }

    private function hasProperty($payload, $property)
    {
        foreach (explode('.', $property) as $segment) {
            if (is_object($payload)) {
                if (!property_exists($payload, $segment)) {
                    return false;
                }
                $payload = $payload->{$segment};
            } elseif (is_array($payload)) {
                if (!array_key_exists($segment, $payload)) {
                    return false;
                }

                $payload = $payload[$segment];
            }
        }

        return true;
    }

    /**
     * @Given /^the response payload contains the following properties:$/
     */
    public function theResponsePayloadContainsTheFollowingProperties(TableNode $table)
    {
        foreach ($table->getHash() as $properties) {
            foreach ($properties as $property => $value) {
                $this->thePropertyEquals($property, $value);
            }
        }
    }

    /**
     * @Then /^the "([^"]*)" property should equal "([^"]*)"$/
     */
    public function thePropertyEquals($property, $expectedValue)
    {
        $payload = $this->getResponseBody();
        $actualValue = $this->getProperty($payload, $property);
        $actualValue = is_bool($actualValue) ? ($actualValue ? "true" : "false") : $actualValue;

        $expectedValue = $this->replacePlaceholders($expectedValue);

        a::assertEquals(
            $expectedValue,
            $actualValue,
            "Asserting the [$property] property in current scope equals [$expectedValue]: ".json_encode($payload)
        );
    }

    /**
     * @Given /^the "([^"]*)" property should contain "([^"]*)"$/
     */
    public function thePropertyShouldContain($property, $expectedValue)
    {
        $payload = $this->getResponseBody();
        $actualValue = $this->getProperty($payload, $property);

        a::assertContains(
            $expectedValue,
            $actualValue,
            "Asserting the [$property] property contains [$expectedValue]: ".json_encode($payload)
        );
    }

    /**
     * @Then /^the "([^"]*)" property should be an array$/
     */
    public function thePropertyIsAnArray($property)
    {
        $payload = $this->getResponseBody();

        $actualValue = $this->getProperty($payload, $property);

        a::assertTrue(
            is_array($actualValue),
            "Asserting the [$property] property is an array: ".json_encode($payload)
        );
    }

    /**
     * @Then /^the "([^"]*)" property array should be empty$/
     */
    public function thePropertyIsAnEmptyArray($property)
    {
        $payload = $this->getResponseBody();

        $actualValue = $this->getProperty($payload, $property);

        a::assertEmpty(
            $actualValue,
            "The [$property] property is not an empty array: ".json_encode($payload)
        );
    }

    /**
     * @Then /^the "([^"]*)" property should contain (\d+) item(?:|s)$/
     */
    public function thePropertyContainsItems($property, $count)
    {
        $payload = $this->getResponseBody();

        a::assertCount(
            (int)$count,
            $this->getProperty($payload, $property),
            "Asserting the [$property] property contains [$count] items: ".json_encode($payload)
        );
    }

    /**
     * @Given /^the "([^"]*)" property should contain the item(?:|s):$/
     */
    public function thePropertyShouldContainTheItems($property, TableNode $table)
    {
        $payload = $this->getResponseBody();
        $actualValue = $this->getProperty($payload, $property);

        if (is_array($actualValue)) {
            foreach ($table->getRowsHash() as $key => $row) {
                $value = $this->replacePlaceholders($row);

                a::assertContains($value, $actualValue[$key]);
            }
        } else {
            foreach ($table->getRows() as $row) {
                $value = $this->replacePlaceholders($row[0]);

                a::assertContains($value, $actualValue);
            }
        }
    }

    private function getProperty($payload, $property)
    {
        foreach (explode('.', $property) as $key) {
            if (is_object($payload)) {
                if (!property_exists($payload, $key)) {
                    throw new \Exception(sprintf('Cannot find the key "%s"', $property));
                }

                $payload = $payload->{$key};
            } elseif (is_array($payload)) {
                if (!array_key_exists($key, $payload)) {
                    throw new \Exception(sprintf('Cannot find the property "%s"', $property));
                }

                $payload = $payload[$key];
            }
        }

        return $payload;
    }

    private function getResponseBody()
    {
        $content = $this->getMainContext()->getSession()->getPage()->getContent();

        return json_decode($content, true);
    }

}
