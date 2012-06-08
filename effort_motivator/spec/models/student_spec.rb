require 'spec_helper'

describe User do

  before do
  	@student = Student.new(name: "Example Student")
  end

  subject { @student }

  it { should respond_to(:name) }
  it { should respond_to(:password_digest) }

end
